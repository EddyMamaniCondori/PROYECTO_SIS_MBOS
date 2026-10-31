CREATE OR REPLACE PROCEDURE sp_reporte_tesoreros_oro_plata(
    p_anio INT,
    p_mes_inicio INT,
    p_mes_fin INT,
    p_alcance VARCHAR,
    p_distritos TEXT,
    p_encargado_id INT,
    p_region VARCHAR,
    p_tipos_iglesia TEXT,
    p_tipo_tesorero TEXT,
    p_ordenar_por VARCHAR,
    p_orden_dir VARCHAR,
    INOUT p_cursor refcursor DEFAULT 'cursor_reporte'
)
LANGUAGE plpgsql AS $$
DECLARE
    v_sql TEXT;
BEGIN
    -- 1. Limpiar tablas temporales si existen de ejecuciones previas
    DROP TABLE IF EXISTS tmp_evaluacion;
    DROP TABLE IF EXISTS tmp_resumen;

    -- 2. Crear la tabla temporal principal
    CREATE TEMP TABLE tmp_evaluacion (
        id_iglesia INT,
        mes INT,
        codigo_iglesia VARCHAR,
        nombre_iglesia VARCHAR,
        tipo_iglesia VARCHAR,
        lugar VARCHAR,
        distrito_id INT,
        nombre_distrito VARCHAR,
        encargado_id INT,
        fecha_limite TIMESTAMP,
        fecha_entrega TIMESTAMP,
        estrellas INT DEFAULT 0,
        dias_retraso INT DEFAULT 0
    );

    -- =========================================================
    -- FASE 1: POBLAR LA TABLA Y ACTUALIZAR DATOS PASO A PASO
    -- =========================================================

    -- UPDATE 1: Insertar todas las iglesias multiplicadas por los meses a evaluar
    INSERT INTO tmp_evaluacion (id_iglesia, mes, codigo_iglesia, nombre_iglesia, lugar,
                tipo_iglesia, distrito_id, nombre_distrito, encargado_id)
    SELECT i.id_iglesia, m.mes, i.codigo, i.nombre, i.lugar, i.tipo, i.distrito_id, d.nombre, d.id_responsable_remesa
    FROM iglesias i
    INNER JOIN distritos d on i.distrito_id = d.id_distrito
    CROSS JOIN generate_series(p_mes_inicio, p_mes_fin) AS m(mes)
    where i.estado = true;

    -- UPDATE 3 y 4: Actualizar las fechas límite y de entrega desde las tablas generas y remesas
    UPDATE tmp_evaluacion t
    SET fecha_limite = r.fecha_limite,
        fecha_entrega = r.fecha_entrega,
        dias_retraso = (r.fecha_entrega::date - r.fecha_limite::date)
    FROM generas g
    JOIN remesas r ON g.id_remesa = r.id_remesa
    WHERE t.id_iglesia = g.id_iglesia
      AND t.mes = g.mes
      AND g.anio = p_anio;

    -- =========================================================
    -- FASE 2: CÁLCULO DE ESTRELLAS POR ZONA
    -- =========================================================

    -- 1. Reglas para EL ALTO (Domingo a Martes = 2 pts | Miércoles = 1 pt | Jueves en adelante = 0 pts)
    UPDATE tmp_evaluacion
    SET estrellas = CASE
        WHEN dias_retraso <= 2 THEN 2
        WHEN dias_retraso = 3 THEN 1
        ELSE 0
    END
    WHERE UPPER(lugar) = 'EL ALTO'
      AND fecha_entrega IS NOT NULL
      AND fecha_limite IS NOT NULL;

    -- 2. Reglas para ALTIPLANO (Domingo al siguiente Lunes = 2 pts | Martes en adelante = 0 pts)
    UPDATE tmp_evaluacion
    SET estrellas = CASE
        WHEN dias_retraso <= 8 THEN 2
        ELSE 0
    END
    WHERE UPPER(lugar) = 'ALTIPLANO'
      AND fecha_entrega IS NOT NULL
      AND fecha_limite IS NOT NULL;

    -- =========================================================
    -- FASE 3: AGRUPAR, CALCULAR ORO/PLATA Y PREPARAR RANKING
    -- =========================================================

    -- Creamos una segunda temporal ya resumida por iglesia
    CREATE TEMP TABLE tmp_resumen AS
    SELECT
        id_iglesia,
        codigo_iglesia,
        nombre_iglesia,
        nombre_distrito,
        tipo_iglesia,
        lugar,
        encargado_id,
        distrito_id,
        SUM(estrellas) AS puntuacion_total,
        COUNT(*) FILTER (WHERE estrellas = 1) AS meses_1_estrella,
        COUNT(*) FILTER (WHERE estrellas = 0) AS meses_0_estrellas,

        -- Guardamos el promedio de la hora exacta de entrega para desempatar el ranking
        SUM(EXTRACT(EPOCH FROM (fecha_entrega - fecha_limite))) AS total_segundos_demora,

        -- Formamos el JSON de detalles mes a mes
        JSON_AGG(
            JSON_BUILD_OBJECT(
                'mes', mes,
                'fecha_limite', fecha_limite,
                'fecha_entrega', fecha_entrega,
                'dias_retraso', dias_retraso,
                'estrellas', estrellas
            ) ORDER BY mes
        ) AS detalles_meses
    FROM tmp_evaluacion
    GROUP BY id_iglesia, codigo_iglesia, nombre_iglesia, nombre_distrito, tipo_iglesia, lugar, encargado_id, distrito_id;


    ALTER TABLE tmp_resumen ADD COLUMN categoria VARCHAR(20);

    UPDATE tmp_resumen
    SET categoria = CASE
        WHEN meses_0_estrellas = 0 AND meses_1_estrella <= 1 THEN 'Oro'
        WHEN meses_0_estrellas = 0 AND meses_1_estrella >= 2 THEN 'Plata'
        ELSE 'Ninguno'
    END;

    -- =========================================================
    -- FASE 4: APLICAR LOS FILTROS DINÁMICOS Y DEVOLVER EL CURSOR
    -- =========================================================

    v_sql := 'SELECT id_iglesia,
                    codigo_iglesia,
                    nombre_iglesia,
                    nombre_distrito,
                    tipo_iglesia,
                    categoria,
                    puntuacion_total,
                    detalles_meses,
                    total_segundos_demora
              FROM tmp_resumen WHERE 1=1 ';

    -- Filtro de Alcance (MBOS no hace nada, trae todo)
    IF p_alcance = 'distrito' THEN
        v_sql := v_sql || ' AND distrito_id = ANY(string_to_array($1, '','')::INT[])';
    ELSIF p_alcance = 'encargado' THEN
        v_sql := v_sql || ' AND encargado_id = $2';
    ELSIF p_alcance = 'region' THEN
        v_sql := v_sql || ' AND UPPER(lugar) = UPPER($3)';
    END IF;

    -- Filtro Tipo Iglesia
    IF POSITION('Todos' IN p_tipos_iglesia) = 0 THEN
        v_sql := v_sql || ' AND tipo_iglesia = ANY(string_to_array($4, '',''))';
    END IF;

    -- Filtro Tipo Tesorero (Oro, Plata, Ambos o Todas)
    IF POSITION('todas_iglesias' IN $5) = 0 THEN
        v_sql := v_sql || ' AND (
               (POSITION(''ambos'' IN $5) > 0 AND categoria IN (''Oro'', ''Plata''))
            OR (POSITION(''oro'' IN $5) > 0 AND categoria = ''Oro'')
            OR (POSITION(''plata'' IN $5) > 0 AND categoria = ''Plata'')
        )';
    END IF;
    -- Ordenamiento y Ranking Final (Aplica desempate por hora)
    IF p_ordenar_por = 'puntuacion' THEN
        IF p_orden_dir = 'DESC' THEN
            -- Los mejores primero (Puntaje más alto). Desempate: tardó menos tiempo (ASC)
            v_sql := v_sql || ' ORDER BY puntuacion_total DESC, total_segundos_demora ASC';
        ELSE
            -- Los peores primero (Puntaje más bajo). Desempate: tardó más tiempo (DESC)
            v_sql := v_sql || ' ORDER BY puntuacion_total ASC, total_segundos_demora DESC';
        END IF;

    ELSIF p_ordenar_por = 'nombre_distrito' THEN
        v_sql := v_sql || ' ORDER BY nombre_distrito ' || p_orden_dir || ', nombre_iglesia ASC';
    ELSE
        v_sql := v_sql || ' ORDER BY nombre_iglesia ' || p_orden_dir;
    END IF;

    -- Abrimos el cursor pasando las variables seguras ($1, $2, $3...) para evitar inyección SQL
    OPEN p_cursor FOR EXECUTE v_sql
    USING p_distritos, p_encargado_id, p_region, p_tipos_iglesia, p_tipo_tesorero;

END;
$$;

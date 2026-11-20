# Pasos para activar la API en el VPS

Ejecuta estos comandos dentro de la carpeta del proyecto en el VPS:

## 1. Instalar Laravel Sanctum
```bash
composer require laravel/sanctum
```

## 2. Publicar la migración de Sanctum
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider" --tag="sanctum-migrations"
```

## 3. Correr la migración (crea la tabla personal_access_tokens)
```bash
php artisan migrate
```

## 4. Agregar HasApiTokens al modelo Persona
Abre `app/Models/Persona.php` y agrega:

```php
use Laravel\Sanctum\HasApiTokens;

class Persona extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    // ... resto del modelo sin cambios
}
```

## 5. Limpiar caché
```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

## 6. Verificar que funciona
```
POST https://TU_VPS/api/login
Content-Type: application/json

{ "email": "pastor@ejemplo.com", "password": "tu_password" }
```

Debe devolver:
```json
{
  "token": "1|xxxxxxxxxxxx",
  "user": {
    "id_persona": 5,
    "nombre": "Juan",
    ...
    "roles": ["Pastor"]
  }
}
```

## 7. Actualizar la URL en la app Flutter
Abre `MBOS_APLICACION/lib/core/constants/api_constants.dart`
y cambia:
```dart
static const String baseUrl = 'https://TU_VPS_URL.com';
```
por la URL real de tu VPS.


#==============================================
    SI EL PROYECTO SE MIGRA A OTRO LADO.
#=============================================

# 1. VERIFICAR que esten estos archivos
    composer.json 
    composer.lock

# 2. Instalar las dependencias para esten en VENDOR/

composer install --no-dev --optimize-autoloader

# regrescar cache.

php artisan config:clear 
php artisan cache:clear
php artisan route:clear

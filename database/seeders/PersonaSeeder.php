<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <<<<< Esto es lo que falta
use Carbon\Carbon;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('personas')->insert([
            'nombre' => 'Eddy',
                'ape_paterno' => 'Mamani',
                'ape_materno' => 'Condori',
                'fecha_nac' => '1997-04-19',
                'ci' => '12892618 LP',
                'celular' => 63072384,
                'ciudad' => 'Tarija',
                'zona' => 'San Roque',
                'calle' => 'Colon',
                'nro' => '8',
                'estado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
        ]);

        DB::table('personas')->insert([
            'nombre' => 'Franklin',
                'ape_paterno' => 'Mamani',
                'ape_materno' => 'Mamani',
                'fecha_nac' => '1997-04-19',
                'ci' => '77382643',
                'celular' => 76543217,
                'ciudad' => 'La Paz',
                'zona' => 'Villa Exaltacion',
                'calle' => '23',
                'nro' => '8',
                'estado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
        ]);
        
        $personas = [
            ['ape_paterno'=>'Aguilar','ape_materno'=>'Conde','nombre'=>'Emmanuel','fecha_nac'=>'1992-10-16','ci'=>'10387002 Chuq.'],
            ['ape_paterno'=>'Aliaga','ape_materno'=>'Quispe','nombre'=>'Fidel','fecha_nac'=>'1977-04-24','ci'=>'4843050 LP'],
            ['ape_paterno'=>'Apaza','ape_materno'=>'Quiñones','nombre'=>'Hugo','fecha_nac'=>'1990-10-03','ci'=>'6915986 LP.'],
            ['ape_paterno'=>'Bautista','ape_materno'=>'Calle','nombre'=>'Miguel Ángel','fecha_nac'=>'1994-06-19','ci'=>'7020616 LP'],
            ['ape_paterno'=>'Chambi','ape_materno'=>'Catunta','nombre'=>'Wilmer Elio','fecha_nac'=>'1994-12-16','ci'=>'9963954 LP.'],
            ['ape_paterno'=>'Chambilla','ape_materno'=>'Esprella','nombre'=>'Rubén','fecha_nac'=>'1987-04-01','ci'=>'6768769 LP.'],
            ['ape_paterno'=>'Chino','ape_materno'=>'Chipana','nombre'=>'John','fecha_nac'=>'1970-01-01','ci'=>'3424647 LP.'],
            ['ape_paterno'=>'Chipana','ape_materno'=>'Serrano','nombre'=>'Mauro','fecha_nac'=>'1982-08-19','ci'=>'6089115 LP.'],
            ['ape_paterno'=>'Chuquimia','ape_materno'=>'Lucana','nombre'=>'Robie','fecha_nac'=>'1997-09-02','ci'=>'10805636 BN'],
            ['ape_paterno'=>'Condori','ape_materno'=>'Canaviri','nombre'=>'Alvaro Gabriel','fecha_nac'=>'1988-05-21','ci'=>'6853028 LP'],
            ['ape_paterno'=>'Coronel','ape_materno'=>'Laime','nombre'=>'Henry Wilson','fecha_nac'=>'1976-10-31','ci'=>'4800042 LP.'],
            ['ape_paterno'=>'Escarzo','ape_materno'=>'Nina','nombre'=>'José Edwin','fecha_nac'=>'1978-12-27','ci'=>'4046655 OR.'],
            ['ape_paterno'=>'Gutierrez','ape_materno'=>'Limachi','nombre'=>'David','fecha_nac'=>'1983-09-13','ci'=>'4774964 LP.'],
            ['ape_paterno'=>'Larico','ape_materno'=>'Aruquipa','nombre'=>'Noel','fecha_nac'=>'1987-05-01','ci'=>'6188628 LP.'],
            ['ape_paterno'=>'Lima','ape_materno'=>'Huanca','nombre'=>'Juan Carlos','fecha_nac'=>'1989-05-16','ci'=>'5033881 Tja.'],
            ['ape_paterno'=>'Llanos','ape_materno'=>'Cohaila','nombre'=>'Adhemar','fecha_nac'=>'1997-10-26','ci'=>'12449941-LP'],
            ['ape_paterno'=>'Llanos','ape_materno'=>'Cohaila','nombre'=>'Omar','fecha_nac'=>'1989-12-23','ci'=>'8304655 LP'],
            ['ape_paterno'=>'Llusco','ape_materno'=>'Chavez','nombre'=>'Edson Plinio','fecha_nac'=>'1989-03-25','ci'=>'6890587 LP'],
            ['ape_paterno'=>'Mamani','ape_materno'=>'Limachi','nombre'=>'Fidel John','fecha_nac'=>'1971-05-24','ci'=>'3478403 CBB.'],
            ['ape_paterno'=>'Mamani','ape_materno'=>'Mamani','nombre'=>'Sergio','fecha_nac'=>'1987-05-11','ci'=>'7005521 LP.'],
            ['ape_paterno'=>'Mena','ape_materno'=>'Laura','nombre'=>'Benjamín Josué','fecha_nac'=>'1998-04-25','ci'=>'12449941 LP'],
            ['ape_paterno'=>'Morante','ape_materno'=>'Kondori','nombre'=>'Manuel Huascar','fecha_nac'=>'1970-09-22','ci'=>'3434083 LP.'],
            ['ape_paterno'=>'Moya','ape_materno'=>'Condori','nombre'=>'Ronald','fecha_nac'=>'1987-11-30','ci'=>'6955431 LP.'],
            ['ape_paterno'=>'Pairo','ape_materno'=>'Mamani','nombre'=>'Ever','fecha_nac'=>'1995-12-15','ci'=>'10062204 LP.'],
            ['ape_paterno'=>'Pari','ape_materno'=>'Tipo','nombre'=>'Mequías','fecha_nac'=>'1982-07-26','ci'=>'6178853 LP.'],
            ['ape_paterno'=>'Pinto','ape_materno'=>'Salvatierra','nombre'=>'Mario Carlos','fecha_nac'=>'1976-12-20','ci'=>'4834871 LP.'],
            ['ape_paterno'=>'Pomacusi','ape_materno'=>'Cari','nombre'=>'Mario Alejandro','fecha_nac'=>'1994-06-19','ci'=>'8354769 LP.'],
            ['ape_paterno'=>'Quipildor','ape_materno'=>'Tito','nombre'=>'Juan Luis','fecha_nac'=>'1964-01-21','ci'=>'2621402 LP.'],
            ['ape_paterno'=>'Quispe','ape_materno'=>'Sánchez','nombre'=>'Josué','fecha_nac'=>'1994-07-26','ci'=>'8404262 LP'],
            ['ape_paterno'=>'Quispe','ape_materno'=>'Espinoza','nombre'=>'Juan Eusebio','fecha_nac'=>'1988-12-07','ci'=>'7055964 LP.'],
            ['ape_paterno'=>'Quiuchaca','ape_materno'=>'Apaza','nombre'=>'Nelo','fecha_nac'=>'1993-05-05','ci'=>'8439312 LP.'],
            ['ape_paterno'=>'Sajama','ape_materno'=>'Sánchez','nombre'=>'David','fecha_nac'=>'1993-04-15','ci'=>'7278224 OR.'],
            ['ape_paterno'=>'Sirpa','ape_materno'=>'Alcon','nombre'=>'Juan Milton','fecha_nac'=>'1989-10-03','ci'=>'7037306 LP.'],
            ['ape_paterno'=>'Tapia','ape_materno'=>'Muñoz','nombre'=>'Nelson','fecha_nac'=>'1984-11-22','ci'=>'6858984 LP.'],
            ['ape_paterno'=>'Tarqui','ape_materno'=>'Chura','nombre'=>'Efrain','fecha_nac'=>'1990-10-30','ci'=>'9239792 LP.'],
            ['ape_paterno'=>'Tarqui','ape_materno'=>'Huanca','nombre'=>'Isaac','fecha_nac'=>'1987-02-01','ci'=>'6875340 LP.'],
            ['ape_paterno'=>'Tarquiola','ape_materno'=>'Coria','nombre'=>'Percy','fecha_nac'=>'1992-03-11','ci'=>'7080473 LP.'],
            ['ape_paterno'=>'Valdez','ape_materno'=>'Laura','nombre'=>'Franz Ciro','fecha_nac'=>'1985-08-18','ci'=>'3461744 LP'],
            ['ape_paterno'=>'Valeriano','ape_materno'=>'Condori','nombre'=>'Absalón Eleazar','fecha_nac'=>'1998-04-14','ci'=>'13440376 LP.'],
        ];

        foreach($personas as $p){
            DB::table('personas')->insert([
                'nombre' => $p['nombre'],
                'ape_paterno' => $p['ape_paterno'],
                'ape_materno' => $p['ape_materno'],
                'fecha_nac' => $p['fecha_nac'],
                'ci' => $p['ci'],
                'celular' => '0',
                'ciudad' => 'La Paz',
                'zona' => ' ',
                'calle' => null,
                'nro' => null,
                'estado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } 
    }
}

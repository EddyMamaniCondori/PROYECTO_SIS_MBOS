<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        //llamamos al seeder de persona

        $this->call([
            PersonaSeeder::class,
            PastorSeeder::class,
            AdministrativoSeeder::class,
            GrupoSeeder::class,
            DistritoSeeder::class,
            IglesiaSeeder::class,
        ]); 
    }
}

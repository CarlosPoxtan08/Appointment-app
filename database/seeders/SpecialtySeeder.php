<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('specialties')->insert([
            ['name' => 'Cardiología', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dermatología', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Neurología', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pediatría', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Traumatología', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Oftalmología', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Otorrinolaringología', 'created_at' => now(), 'update_at' => now()],
            ['name' => 'Ginecología', 'created_at' => now(), 'update_at' => now()],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NazioniTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/nazioni.csv');
        $rows = array_map('str_getcsv', file($path));
        foreach ($rows as $row){
            DB::table('nazioni')->insert([
                'sigla' => $row[0],
                'nome' => $row[1],
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Dossier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DossieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dossier = [
            [
                'name' => 'teeest'
            ]
        ];

        Dossier::insert($dossier);
    }
}

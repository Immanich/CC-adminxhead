<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            ['code' => 'en', 'name' => 'English'],
            ['code' => 'fil', 'name' => 'Filipino'],
        ];

        foreach ($languages as $language) {
            DB::table('languages')->insert($language);
        }
    }
}

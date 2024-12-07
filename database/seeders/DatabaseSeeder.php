<?php

namespace Database\Seeders;

use App\Models\Transaction;
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
        $this->call(RoleSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(OfficeSeeder::class);
        $this->call(TransactionSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(ServicesInfoSeeder::class);
        $this->call(EventSeeder::class);
        $this->call(LanguageSeeder::class);
        // $this->call(OfficeTranslationSeeder::class);
        // $this->call(ServiceTranslationSeeder::class);
        $this->call(MvmspSeeder::class);
        $this->call(ElectedOfficialSeeder::class);

    }
}

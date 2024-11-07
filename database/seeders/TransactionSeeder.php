<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactions = [
            [
                'type_of_transaction' => 'G2G-Government to Government',
            ],
            [
                'type_of_transaction' => "G2C, Government to CLIENT",
            ],
            [
                'type_of_transaction' => 'G2C-Gov Service to transacting public',
            ],
            [
                'type_of_transaction' => 'G2B - Gov Service to business entity',
            ],
            [
                'type_of_transaction' => 'G2C - Gov Service to Gov',
            ],
            [
                'type_of_transaction' => "G2C - Gov to public clients",
            ],
            [
                'type_of_transaction' => 'G2C - Gov to Public',
            ],
            [
                'type_of_transaction' => 'G2C - Gov to Public Transact',
            ],
            [
                'type_of_transaction' => 'G2C - for Government Services whose client is the transacting public',
            ],
            [
                'type_of_transaction' => 'For government services whose client is a business entity',
            ],
            [
                'type_of_transaction' => 'For government services whose client is the transacting public',
            ],
            [
                'type_of_transaction' => 'For Government Service whose client is a government employee or another government agency',
            ],
            [
                'type_of_transaction' => 'G2G – G2C',
            ],
            [
                'type_of_transaction' => 'G2C - G2B - G2G',
            ],
        ];

        foreach ($transactions as $transaction) {
            Transaction::create($transaction);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DisbursementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Disbursement::factory()->count(5)->create();
    }
}

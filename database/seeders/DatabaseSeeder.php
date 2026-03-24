<?php

namespace Database\Seeders;

use App\Models\Donation;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Payment Methods
        $banks = [
            ['bank_name' => 'BSI', 'account_number' => '7123456789', 'account_name' => 'Lembaga Wakaf Sedekah'],
            ['bank_name' => 'BCA', 'account_number' => '8123456789', 'account_name' => 'Lembaga Wakaf Sedekah'],
            ['bank_name' => 'Mandiri', 'account_number' => '13123456789', 'account_name' => 'Lembaga Wakaf Sedekah'],
            ['bank_name' => 'BRI', 'account_number' => '0123456789012', 'account_name' => 'Lembaga Wakaf Sedekah'],
        ];

        foreach ($banks as $bank) {
            PaymentMethod::create($bank);
        }

        // Create Sample Donations
        $donations = [
            ['name' => 'Ahmad Hidayat', 'type' => 'donasi', 'amount' => 500000, 'status' => 'approved', 'anonymous' => false],
            ['name' => 'Fatimah Zahra', 'type' => 'zakat', 'amount' => 1500000, 'status' => 'approved', 'anonymous' => false],
            ['name' => 'Hamba Allah', 'type' => 'wakaf', 'amount' => 2000000, 'status' => 'approved', 'anonymous' => true],
            ['name' => 'Ibrahim Rahman', 'type' => 'donasi', 'amount' => 250000, 'status' => 'approved', 'anonymous' => false],
            ['name' => 'Khadijah', 'type' => 'zakat', 'amount' => 3000000, 'status' => 'approved', 'anonymous' => false],
            ['name' => 'Hamba Allah', 'type' => 'donasi', 'amount' => 100000, 'status' => 'pending', 'anonymous' => true],
            ['name' => 'Musa Abdullah', 'type' => 'wakaf', 'amount' => 5000000, 'status' => 'pending', 'anonymous' => false],
            ['name' => 'Aisyah Putri', 'type' => 'donasi', 'amount' => 150000, 'status' => 'rejected', 'anonymous' => false],
        ];

        $paymentMethodIds = PaymentMethod::pluck('id')->toArray();

        foreach ($donations as $donation) {
            Donation::create([
                'trx_id' => 'TRX-' . strtoupper(Str::random(8)),
                'type' => $donation['type'],
                'donatur_name' => $donation['anonymous'] ? 'Hamba Allah' : $donation['name'],
                'amount' => $donation['amount'],
                'payment_method_id' => $paymentMethodIds[array_rand($paymentMethodIds)],
                'status' => $donation['status'],
                'is_anonymous' => $donation['anonymous'],
            ]);
        }
    }
}

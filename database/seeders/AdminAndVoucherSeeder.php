<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminAndVoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed / Update Admin User
        User::updateOrCreate(
            ['email' => 'admin@foodie.test'],
            [
                'name' => 'Admin Foodie',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Seed Vouchers
        $vouchers = [
            [
                'code' => 'FOODIEHEMAT',
                'name' => 'Diskon Spesial Foodie Hemat',
                'discount_type' => 'fixed',
                'discount_value' => 15000,
                'min_spend' => 40000,
                'max_discount' => null,
                'quota' => 200,
                'used_count' => 5,
                'valid_until' => now()->addMonths(3)->toDateString(),
                'is_active' => true,
            ],
            [
                'code' => 'DISKON20',
                'name' => 'Diskon Kilat 20% All Menu',
                'discount_type' => 'percentage',
                'discount_value' => 20,
                'min_spend' => 50000,
                'max_discount' => 25000,
                'quota' => 150,
                'used_count' => 12,
                'valid_until' => now()->addMonths(2)->toDateString(),
                'is_active' => true,
            ],
            [
                'code' => 'GRATISONGKIR',
                'name' => 'Potongan Ongkir Rp 12.000',
                'discount_type' => 'fixed',
                'discount_value' => 12000,
                'min_spend' => 30000,
                'max_discount' => null,
                'quota' => 300,
                'used_count' => 28,
                'valid_until' => now()->addMonths(6)->toDateString(),
                'is_active' => true,
            ],
            [
                'code' => 'SUPERDEAL50',
                'name' => 'Super Deal Pesta Makanan 50%',
                'discount_type' => 'percentage',
                'discount_value' => 50,
                'min_spend' => 70000,
                'max_discount' => 40000,
                'quota' => 50,
                'used_count' => 18,
                'valid_until' => now()->addMonths(1)->toDateString(),
                'is_active' => true,
            ],
        ];

        foreach ($vouchers as $voucher) {
            Voucher::updateOrCreate(['code' => $voucher['code']], $voucher);
        }
    }
}

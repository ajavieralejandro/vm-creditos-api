<?php

namespace Database\Seeders;

use App\Models\CreditPack;
use Illuminate\Database\Seeder;

class CreditPackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packs = [
            [
                'code' => 'pack_10',
                'name' => 'Pack 10 créditos',
                'credits_amount' => 10,
                'price_amount' => 1000_00,
                'description' => 'Paquete de 10 créditos.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'code' => 'pack_25',
                'name' => 'Pack 25 créditos',
                'credits_amount' => 25,
                'price_amount' => 2200_00,
                'description' => 'Paquete de 25 créditos.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'code' => 'pack_50',
                'name' => 'Pack 50 créditos',
                'credits_amount' => 50,
                'price_amount' => 4000_00,
                'description' => 'Paquete de 50 créditos.',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'code' => 'pack_100',
                'name' => 'Pack 100 créditos',
                'credits_amount' => 100,
                'price_amount' => 7500_00,
                'description' => 'Paquete de 100 créditos.',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($packs as $pack) {
            CreditPack::updateOrCreate(
                ['code' => $pack['code']],
                $pack,
            );
        }
    }
}

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
                'credits' => 10,
                'price_ars' => 1000,
                'description' => 'Paquete de 10 créditos.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'code' => 'pack_20',
                'name' => 'Pack 20 créditos',
                'credits' => 20,
                'price_ars' => 1800,
                'description' => 'Paquete de 20 créditos.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'code' => 'pack_50',
                'name' => 'Pack 50 créditos',
                'credits' => 50,
                'price_ars' => 4000,
                'description' => 'Paquete de 50 créditos.',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'code' => 'pack_100',
                'name' => 'Pack 100 créditos',
                'credits' => 100,
                'price_ars' => 7500,
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

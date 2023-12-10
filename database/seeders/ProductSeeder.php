<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name'  => 'Produto 1',
                'price' => 12.49,
                'photo' => 'products/default.png',
            ],
            [
                'name'  => 'Produto 2',
                'price' => 34.49,
                'photo' => 'products/default.png',
            ],
        ];

        foreach ($products as $product) {
            $product = new Product($product);
            $product->save();
        }
    }
}

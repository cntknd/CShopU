<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSizesTableSeeder extends Seeder
{
    public function run(): void
    {
        $sizes = ['Small', 'Medium', 'Large', 'X-Large'];

        foreach ($sizes as $size) {
            DB::table('product_sizes')->insert([
                'size_name' => $size,
                'stock' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    $categories = [
        'PE T-shirt', 'PE Jogging pants', 'NSTP T-shirt', 'POLO with Logo',
        'CHM ORG.', 'CICS ORG.', 'CCJE ORG.', 'CBEA ORG.', 'CIT ORG.', 'CTED ORG.', 'CFAS ORG.',
        'ID Strap', 'Bomber Jacket', 'Vehicle Pass', 'Coffee Mug', 'Customized Badge',
        'Customized Pen', 'Hoodie Jacket', 'Insulated Water Tumbler', 'Keychain', 'Paper Bag',
        'Paper Weight', 'Ranger Hat', 'Tote Bag', 'Hawks T-shirt',
    ];

    foreach ($categories as $name) {
        Category::create(['name' => $name]);
    }
}
}

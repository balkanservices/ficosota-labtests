<?php

use Illuminate\Database\Seeder;

use App\Product;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$products = [
			[
				'brand'	=>	'PUFIES MAXI',
				'sub_brand'	=>	'Baby Art',
				'conception' => '',
                'size' => '',
                'kg_range' => '',
                'region' => '',
                'market' => '',
                'year' => '',
                'quarter' => '',
                'country_of_origin' => '',
                'producer' => '',
                'date_of_production' => '',
                'batch' => '',
			]
		];
		
		foreach($products as $productArr) {
			$product = new Product($productArr);
			$product->save();
		}
    }
}

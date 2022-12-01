<?php

use Illuminate\Database\Seeder;
use App\Sample;

class SamplesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$samples = [
			[
				'weight'				=>	'38.04',
				'samples_package_id'    =>	1,
				'wip'					=>	0,
			],
			[
				'weight'				=>	'38.08',
				'samples_package_id'    =>	1,
				'wip'					=>	0,
			],
			[
				'weight'				=>	'37.85',
				'samples_package_id'    =>	1,
				'wip'					=>	0,
			],
			[
				'weight'				=>	'37.86',
				'samples_package_id'    =>	1,
				'wip'					=>	0,
			],
		];
		
		foreach($samples as $sampleArr) {
			$sample = new Sample($sampleArr);
            $sample->samples_package_id = 1;
			$sample->save();
		}
    }
}

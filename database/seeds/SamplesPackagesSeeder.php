<?php

use Illuminate\Database\Seeder;
use App\SamplesPackage;

class SamplesPackagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$samplesPackages = [
			[
				'manifacturing_time'	=>	'15:10',
				'samples_count'         =>	10,
			],
		];
		
		foreach($samplesPackages as $samplesPackageArr) {
			$samplesPackage = new SamplesPackage($samplesPackageArr);
            $samplesPackage->samples_list_id = 1;
			$samplesPackage->save();
		}
    }
}

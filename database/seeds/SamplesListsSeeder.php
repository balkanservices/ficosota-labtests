<?php

use Illuminate\Database\Seeder;
use App\SamplesList;

class SamplesListsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$samplesLists = [
			[
				'qa_journal_id'		=>	1,
                'name'              => 'Тест'
			],
		];
		
		foreach($samplesLists as $samplesListArr) {
			$samplesList = new SamplesList($samplesListArr);
			$samplesList->save();
		}
    }
}

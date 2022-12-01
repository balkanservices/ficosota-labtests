<?php

use Illuminate\Database\Seeder;
use App\Helpers\AnalysisDefinitionSeederHelper;

class AnalysisDefinitionSeeder3 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $definitions = [
            'construction',
            'color_stability',
        ];

        foreach($definitions as $definitionFile) {
            AnalysisDefinitionSeederHelper::seedOrUpdate($definitionFile);
		}
    }
}

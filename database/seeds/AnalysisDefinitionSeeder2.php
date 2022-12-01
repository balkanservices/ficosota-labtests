<?php

use Illuminate\Database\Seeder;
use App\Helpers\AnalysisDefinitionSeederHelper;

class AnalysisDefinitionSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $definitions = [
            'grammage_of_materials',
            'absorbtion_before_leakage',
            'elastic_bands_lengths',
            'sap_bromcresole',
            'sap_bromphenol',
            'sap_distilled_water',
        ];

        foreach($definitions as $definitionFile) {
            AnalysisDefinitionSeederHelper::seedOrUpdate($definitionFile);
		}
    }
}

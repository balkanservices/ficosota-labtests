<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\AnalysisDefinitionSeederHelper;

class AddAnalysisCompleteOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        AnalysisDefinitionSeederHelper::seedOrUpdate('absorbtion_before_leakage');
        AnalysisDefinitionSeederHelper::seedOrUpdate('absorption_against_pressure');
        AnalysisDefinitionSeederHelper::seedOrUpdate('centrifuge_retention_capacity');
        AnalysisDefinitionSeederHelper::seedOrUpdate('color_stability');
        AnalysisDefinitionSeederHelper::seedOrUpdate('construction');
        AnalysisDefinitionSeederHelper::seedOrUpdate('creep_resistance_1h_40_degrees');
        AnalysisDefinitionSeederHelper::seedOrUpdate('creep_resistance_4h_40_degrees');
        AnalysisDefinitionSeederHelper::seedOrUpdate('elastic_bands_lengths');
        AnalysisDefinitionSeederHelper::seedOrUpdate('grammage_of_materials');
        AnalysisDefinitionSeederHelper::seedOrUpdate('sap_bromcresole');
        AnalysisDefinitionSeederHelper::seedOrUpdate('sap_bromphenol');
        AnalysisDefinitionSeederHelper::seedOrUpdate('sap_distilled_water');
        AnalysisDefinitionSeederHelper::seedOrUpdate('superabsorbent_quantity');
        AnalysisDefinitionSeederHelper::seedOrUpdate('superabsorbent_quantity_zones');
        AnalysisDefinitionSeederHelper::seedOrUpdate('total_absorption_capacity');
        AnalysisDefinitionSeederHelper::seedOrUpdate('wetting_value');
        AnalysisDefinitionSeederHelper::seedOrUpdate('wetting_value_under_pressure');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\AnalysisDefinitionSeederHelper;
use Illuminate\Support\Facades\DB;

class ModifyAnalyses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        AnalysisDefinitionSeederHelper::seedOrUpdate('superabsorbent_quantity');

        DB::table('analysis_attributes')
            ->where('attribute', 'sap_quantity__blank_value')
            ->update(['attribute' => 'weights__blank_value']);

        DB::table('analysis_attributes')
            ->where('attribute', 'sap_quantity__consumption_1')
            ->update(['attribute' => 'weights__consumption_1']);

        DB::table('analysis_attributes')
            ->where('attribute', 'sap_quantity__consumption_2')
            ->update(['attribute' => 'weights__consumption_2']);

        DB::table('analysis_attributes')
            ->where('attribute', 'sap_quantity__ca_bondability')
            ->update(['attribute' => 'weights__ca_bondability']);


        DB::table('analysis_attributes')
            ->where('attribute', 'weights__weight')
            ->update(['attribute' => 'sap_quantity__weight']);

        DB::table('analysis_attributes')
            ->where('attribute', 'weights__core_weight')
            ->update(['attribute' => 'sap_quantity__core_weight']);

        DB::table('analysis_attributes')
            ->where('attribute', 'weights__curly_fibers')
            ->update(['attribute' => 'sap_quantity__curly_fibers']);

        DB::table('analysis_attributes')
            ->where('attribute', 'weights__materials_mass')
            ->update(['attribute' => 'sap_quantity__materials_mass']);


        AnalysisDefinitionSeederHelper::seedOrUpdate('creep_resistance_1h_40_degrees');
        AnalysisDefinitionSeederHelper::seedOrUpdate('creep_resistance_4h_40_degrees');
        AnalysisDefinitionSeederHelper::seedOrUpdate('elastic_bands_lengths');
        AnalysisDefinitionSeederHelper::seedOrUpdate('construction');

        $foamRubberArr = [
            'front_foam_rubber_color', 'front_foam_rubber_width', 'front_foam_rubber_front_length',
            'front_foam_rubber_middle_length', 'front_foam_rubber_back_length', 'back_foam_rubber_color',
            'back_foam_rubber_width', 'back_foam_rubber_front_length', 'back_foam_rubber_middle_length',
            'back_foam_rubber_back_length'
        ];

        foreach($foamRubberArr as $foamRubberElement) {
            DB::table('analysis_attributes')
                ->where('attribute', 'foam_rubber__' . $foamRubberElement)
                ->update(['attribute' => 'waist_band__foam_rubber__' . $foamRubberElement]);
        }

        $tissueTapeArr = [
            'front_tissue_tape_free_state_width', 'front_tissue_tape_stretched_state_width',
            'front_tissue_tape_length', 'back_tissue_tape_free_state_width',
            'back_tissue_tape_stretched_state_width', 'back_tissue_tape_length'
        ];

        foreach($tissueTapeArr as $tissueTapeElement) {
            DB::table('analysis_attributes')
                ->where('attribute', 'tissue_tape__' . $tissueTapeElement)
                ->update(['attribute' => 'waist_band__tissue_tape__' . $tissueTapeElement]);
        }

        AnalysisDefinitionSeederHelper::seedOrUpdate('absorbtion_before_leakage');
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

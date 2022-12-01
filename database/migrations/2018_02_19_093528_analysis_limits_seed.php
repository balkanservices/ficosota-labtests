<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\AnalysisLimitsSeederHelper;
use App\Helpers\LanguageLineHelper;

class AnalysisLimitsSeed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $types = [
            'new_born',
            'mini',
            'midi',
            'maxi',
            'maxi_plus',
            'junior',
        ];

        $doubleTypes = [
            'puffies_sensitive',
            'puffies_art_and_dry',
        ];

        $doubleAnalyses = [
            'construction',
        ];

        $analyses = [
            'wetting_value',
            'wetting_value_under_pressure',
            'wetting_value_under_pressure_averages',
            'elastic_bands_lengths',
            'superabsorbent_quantity',
            'superabsorbent_quantity_averages',
            'total_absorption_capacity',
            'creep_resistance_1h_40_degrees',
            'diaper_weights',
        ];

        foreach($types as $type) {
            foreach($doubleAnalyses as $doubleAnalysis) {
                foreach($doubleTypes as $doubleType) {
                    AnalysisLimitsSeederHelper::seedOrUpdate($type . '_' . $doubleType . '_' . $doubleAnalysis, $type . '_' . $doubleType);
                }
            }

            foreach($analyses as $analysis) {
                foreach($doubleTypes as $doubleType) {
                    AnalysisLimitsSeederHelper::seedOrUpdate($type . '_' . $analysis, $type . '_' . $doubleType);
                }
            }
        }

        $doubleTypesLabels = [
            'puffies_sensitive' => 'Sensitive',
            'puffies_art_and_dry' => 'Baby Art & Dry',
        ];

        $typesLabels = [
            'new_born' => 'New Born',
            'mini' => 'Mini',
            'midi' => 'Midi',
            'maxi' => 'Maxi',
            'maxi_plus' => 'Maxi+',
            'junior' => 'Junior',
        ];

        foreach($doubleTypesLabels as $doubleType => $doubleTypeLabel) {
            foreach($typesLabels as $type => $typeLabel) {
                LanguageLineHelper::updateOrCreateLanguageLine("analysis_limits", $type . '_' . $doubleType, ["bg" => $typeLabel . ' ' . $doubleTypeLabel]);
            }
        }

        Schema::table('products', function($table) {
            $table->string('analysis_limits')->nullable();
        });

        LanguageLineHelper::updateOrCreateLanguageLine("products", 'analysis_limits', ["bg" => 'Performance limits']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function($table) {
            $table->dropColumn('analysis_limits');
        });
    }
}

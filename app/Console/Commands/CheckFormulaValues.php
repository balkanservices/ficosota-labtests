<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Analysis;
use App\Helpers\FormulaHelper;

class CheckFormulaValues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:formula-values';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks if calculations are correct for the current formulas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sampleAnalyses = Analysis::all();
                
        foreach ($sampleAnalyses as $sampleAnalysis) {

            if ($sampleAnalysis->sample->deleted
               || $sampleAnalysis->sample->package->samples_list->deleted) {
                continue;
            }
            
            $headerShown = false;
            $definition = $sampleAnalysis->definition;
            
            $definitionFormulaFields = $definition->getFormulaFields();
            
            $attributesArr = $sampleAnalysis->getAttributesArray();
            foreach ($definitionFormulaFields as $definitionFormulaField) {
                $analysisAttribute = $sampleAnalysis->attributes()->where('attribute', $definitionFormulaField['name'])->first();
                if (!$analysisAttribute) {
                    $currentValue = null;
                } else {
                    $currentValue = $analysisAttribute->value;
                    if ($currentValue) {
                        $currentValue = round($analysisAttribute->value, 2);
                    }
                }
                
                $calculatedValue = FormulaHelper::calculateValue($definitionFormulaField, $attributesArr);
                
                if ($calculatedValue) {
                    $calculatedValue = round($calculatedValue, 2);
                }
                if ($currentValue != $calculatedValue) {
                    if (!$headerShown) {
                        echo route('samples_list.do_analysis', [
                            '_locale' => 'bg',
                            'samplesPackageId' => $sampleAnalysis->sample->samples_package_id,
                            'analysis' => $definition->slug,
                            'samplesIds' => $sampleAnalysis->sample->id,
                        ]) . "\n";
                        $headerShown = true;
                    }
                    echo __('samples_list.analysis_fields.' 
                            . $definition->slug
                            . '.'
                            . $definitionFormulaField['name']) 
                            . ': в момента е "' 
                            . $currentValue 
                            . '", трябва да бъде "' 
                            . $calculatedValue 
                            . "\"\n\n";
                }
                
            }
        }
    }
    
    
}




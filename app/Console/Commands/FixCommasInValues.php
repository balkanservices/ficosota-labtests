<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Analysis;

class FixCommasInValues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:commas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fixes commas in attribute values';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $results = DB::select(DB::raw("select distinct(analysis_id) as analysis_id from analysis_attributes WHERE value REGEXP '^[0-9,]+$' and LOCATE(',', value) > 0"));
        foreach ($results as $result) {
            $analysisId = $result->analysis_id;
            $sampleAnalysis = Analysis::findOrFail($analysisId);
            foreach ($sampleAnalysis->getAttributesArray() as $field => $value) {
                $newValue = str_replace(',', '.', $value);
                if (strpos($value, ',') !== false && is_numeric($newValue)) {
                    try {
                        $sampleAnalysis->setAttributeValue($field, str_replace(',', '.', $newValue));
                    } catch (\Exception $e) {
                        
                    }
                }
            }
        }

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SamplesPackageDiaperWeights;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\AnalysisDefinition;
use App\Analysis;
use Illuminate\Support\Facades\DB;

class SamplesPackage extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = ['manifacturing_time', 'samples_count', 'comment', 'enabled_analyses'];

    public function samples_list()
    {
        return $this->belongsTo('App\SamplesList');
    }

    public function samples()
    {
        return $this->hasMany('App\Sample')->where('deleted', 0);
    }

    public function diaperWieghts()
    {
        return $this->hasOne('App\SamplesPackageDiaperWeights');
    }

    public function recalculateWeights()
    {
        $diaperWeights = SamplesPackageDiaperWeights::where('samples_package_id', '=', $this->id)->first();

        if($diaperWeights === null) {
            $diaperWeights = new SamplesPackageDiaperWeights();
            $diaperWeights->samples_package_id = $this->id;
            $diaperWeights->average = 0;
            $diaperWeights->min = 0;
            $diaperWeights->max = 0;
            $diaperWeights->standard_deviation = 0;
            $diaperWeights->delta = 0;
            $diaperWeights->count = 0;
            $diaperWeights->save();
        }

        $totalWeight = 0;
        $samplesCount = 0;
        $diaperWeights->min = 1000000;
        $diaperWeights->max = 0;

        $samples = $this->samples()->get();
        foreach($samples as $sample) {
            if($diaperWeights->min > $sample->weight) {
                $diaperWeights->min = $sample->weight;
            }

            if($diaperWeights->max < $sample->weight) {
                $diaperWeights->max = $sample->weight;
            }

            $totalWeight += $sample->weight;
            $samplesCount++;
        }

        if($samplesCount == 0 ) {
            return;
        }

        $diaperWeights->average = round($totalWeight / $samplesCount, 2);
        $diaperWeights->delta = round($diaperWeights->max - $diaperWeights->min, 2);


        //calculate standard deviation
        $mean = $diaperWeights->average;
        $sqMeanDiffTotal = 0;
        $samplesCount = 0;
        foreach($this->samples as $sample) {
            $sqMeanDiffTotal += pow($sample->weight - $mean,2);
            $samplesCount++;
        }

        $newMean = $sqMeanDiffTotal / $samplesCount;


        $diaperWeights->standard_deviation = round(sqrt($newMean), 2);

        $diaperWeights->count = $samplesCount;

        $diaperWeights->save();
    }

    public function getSamplesIdsArr() {
        $samples = $this->samples()->get();
        $samplesIdsArr = [];
        foreach($samples as $sample) {
            if ($sample->deleted) {
                continue;
            }
            $samplesIdsArr[] = $sample->id;
        }

        return $samplesIdsArr;
    }

    public function getAnalysisSamplesIdsArr($analysisSlug, $attribute = null, $value = null) {
        $query = DB::table('samples')
            ->leftJoin('analysis', 'samples.id', '=', 'analysis.sample_id');
        
        if (!empty($attribute)) {
            $query->leftJoin('analysis_attributes', 'analysis.id', '=', 'analysis_attributes.analysis_id');
        }
        
        $query->leftJoin('analysis_definitions', 'analysis.analysis_definition_id', '=', 'analysis_definitions.id')
            ->select('samples.id')
            ->where('analysis.enabled', '=', 1)
            ->where('samples.samples_package_id', '=', $this->id)
            ->where('samples.deleted', '=', '0')
            ->where('analysis_definitions.slug', '=', $analysisSlug);
        
        if (!empty($attribute)) {
            $query->where('analysis_attributes.attribute', '=', $attribute);
            $query->where('analysis_attributes.value', '=', $value);
        }

        return $query->pluck('id')->toArray();
    }

    private function getAverageSampleWeight($samplesWeights) {
        $samplesTotalWeight = 0;
        $samplesCount = 0;
        foreach($samplesWeights as $sample) {
            $samplesTotalWeight += $sample['weight'];
            $samplesCount++;
        }

        return round($samplesTotalWeight / $samplesCount, 2);
    }

    private function getSamplesWeights() {
        $samples = $this->samples()->get();
        $samplesWeights = [];

        foreach($samples as $sample) {
            $samplesWeights[] = [
                'id'    =>  $sample->id,
                'weight'=>  $sample->weight,
            ];
        }

        return $samplesWeights;
    }

    private function shift(&$array, $count) {
        $shiftedValues = [];
        for ($i = 0; $i < $count; $i++) {
            $shiftedValues[] = array_shift($array);
        }

        return $shiftedValues;
    }

    private function pop(&$array, $count) {
        $shiftedValues = [];
        for ($i = 0; $i < $count; $i++) {
            $shiftedValues[] = array_pop($array);
        }

        return $shiftedValues;
    }

    private function sortMinMax(&$array) {
        usort($array, function($a, $b){
            if ($a['weight'] == $b['weight']) {
                return 0;
            }

            return $a['weight'] > $b['weight'] ? 1 : -1;
        });
    }

    private function sortDistanceFromAvg(&$array, $avgValue) {
        usort($array, function($a, $b) use ($avgValue){

            $aAvgDiff = abs( $avgValue - $a['weight']);
            $bAvgDiff = abs( $avgValue - $b['weight']);

            if ($aAvgDiff == $bAvgDiff) {
                return 0;
            }

            return $aAvgDiff > $bAvgDiff ? 1 : -1;
        });
    }

    private function random(&$array, $count) {
        if (count($array) < $count) {
            throw new \Exception('Not enough values!');
        }

        $array = array_values($array);

        $randomIndexes = [];
        while (count($randomIndexes) < $count) {
            $randomIndexes[random_int(0, count($array) - 1)] = 1;
        }

        $randomValues = [];
        foreach (array_keys($randomIndexes) as $index) {
            $randomValues[] = $array[$index];
            unset($array[$index]);
        }

        return $randomValues;
    }

    public function hasMinimumCountForAssignments() {
        if ($this->samples()->count() >= $this->getMinimumCountForAssignments()) {
            return true;
        }

        return false;
    }

    public function getMinimumCountForAssignments() {
        $randomValues = [];
        for($i=0; $i<100; $i++) {
            $randomValues[] = [
                'id'    =>  $i + 1,
                'weight'=> random_int(1, 1000),
            ];
        }
        $assignments = $this->getAssignmentsArrForWeights($randomValues);

        $minimumRecords = [];
        foreach ($assignments as $assignmentWeights) {
            foreach ($assignmentWeights as $sampleWeight) {
                $minimumRecords[$sampleWeight['id']] = 1;
            }
        }

        return count(array_keys($minimumRecords));
    }


    public function getAssignmentsArr() {
        $samplesWeights = $this->getSamplesWeights();

        return $this->getAssignmentsArrForWeights($samplesWeights);
    }

    public function getAssignmentsArrForWeights($samplesWeights) {
        $enabledAnalyses = $this->getEnabledAnalyses();

        $samplesAverageWeight = $this->getAverageSampleWeight($samplesWeights);

        // Sort by min/max weight
        $this->sortMinMax($samplesWeights);

        $assignments = [];

        if (in_array('superabsorbent_quantity', $enabledAnalyses)) {
            $assignments['superabsorbent_quantity'][] = array_pop($samplesWeights);
            $assignments['superabsorbent_quantity'][] = array_shift($samplesWeights);
        }

        // Sort by distance from average weight
        $this->sortDistanceFromAvg($samplesWeights, $samplesAverageWeight);

        if (in_array('superabsorbent_quantity', $enabledAnalyses)) {
            $assignments['superabsorbent_quantity'] = array_merge(
                $assignments['superabsorbent_quantity'],
                $this->shift($samplesWeights, 3)
            );
        }

        if (in_array('creep_resistance_1h_40_degrees', $enabledAnalyses)) {
            if (in_array('superabsorbent_quantity', $enabledAnalyses)) {
                $assignments['creep_resistance_1h_40_degrees'] = array_slice($assignments['superabsorbent_quantity'], 0, 3);
            } else {
                $assignments['creep_resistance_1h_40_degrees'] = $this->shift($samplesWeights, 3);
            }
        }

        if (in_array('creep_resistance_4h_40_degrees', $enabledAnalyses)) {
            if (in_array('superabsorbent_quantity', $enabledAnalyses)) {
                $assignments['creep_resistance_4h_40_degrees'] = array_slice($assignments['superabsorbent_quantity'], 3, 2);
            } else {
                $assignments['creep_resistance_4h_40_degrees'] = $this->shift($samplesWeights, 2);
            }
        }

        if (in_array('wetting_value_under_pressure', $enabledAnalyses)) {
            $assignments['wetting_value_under_pressure'] = $this->shift($samplesWeights, 5);
        }

        if (in_array('wetting_value', $enabledAnalyses)) {
            $assignments['wetting_value'] = $this->shift($samplesWeights, 5);
        }

        if (in_array('total_absorption_capacity', $enabledAnalyses)) {
            $assignments['total_absorption_capacity'] = $this->shift($samplesWeights, 5);
        }

        $this->sortMinMax($samplesWeights);

        if (in_array('sap_distilled_water', $enabledAnalyses)) {
            $assignments['sap_distilled_water'] = $this->pop($samplesWeights, 3);
        }

        if (in_array('elastic_bands_lengths', $enabledAnalyses)) {
            if (in_array('sap_distilled_water', $enabledAnalyses)) {
                $assignments['elastic_bands_lengths'] = $assignments['sap_distilled_water'];
            } else {
                $assignments['elastic_bands_lengths'] = $this->pop($samplesWeights, 3);
            }
        }

        if (in_array('absorbtion_before_leakage', $enabledAnalyses)) {
            $assignments['absorbtion_before_leakage'] = $this->random($samplesWeights, 15);
        }

        if (in_array('sap_bromcresole', $enabledAnalyses)) {
            $assignments['sap_bromcresole'] = $this->random($samplesWeights, 3);
        }

        if (in_array('sap_bromphenol', $enabledAnalyses)) {
            $assignments['sap_bromphenol'] = $this->random($samplesWeights, 5);
        }

        if (in_array('grammage_of_materials', $enabledAnalyses)) {
            $assignments['grammage_of_materials'] = $this->random($samplesWeights, 3);
        }

        if (in_array('centrifuge_retention_capacity', $enabledAnalyses)) {
            $assignments['centrifuge_retention_capacity'] = $this->random($samplesWeights, 1);
        }

        if (in_array('absorption_against_pressure', $enabledAnalyses)) {
            $assignments['absorption_against_pressure'] = $this->random($samplesWeights, 1);
        }

        if (in_array('creep_resistance_4h_40_degrees', $enabledAnalyses)) {
            if (in_array('centrifuge_retention_capacity', $enabledAnalyses)) {
                $assignments['creep_resistance_4h_40_degrees'] = array_merge(
                    $assignments['creep_resistance_4h_40_degrees'],
                    [$assignments['centrifuge_retention_capacity'][0]]
                );
            } else {
                $assignments['creep_resistance_4h_40_degrees'] = array_merge(
                    $assignments['creep_resistance_4h_40_degrees'],
                    $this->random($samplesWeights, 1)
                );
            }
        }

        if (in_array('construction', $enabledAnalyses)) {
            $assignments['construction'] = $this->random($samplesWeights, 3);
        }

        if (in_array('color_stability', $enabledAnalyses)) {
            $assignments['color_stability'] = $this->random($samplesWeights, 3);
        }

        if (in_array('barrier_leaks', $enabledAnalyses)) {
            $assignments['barrier_leaks'] = $this->random($samplesWeights, 3);
        }

        return $assignments;
    }

    public function getColorStabilitySamplesIdsArr() {
        return $this->getAnalysisSamplesIdsArr('color_stability');
    }

    public function getEnabledAnalyses() {
        $enabledAnalyses = json_decode($this->enabled_analyses);

        if (!is_array($enabledAnalyses) || empty($enabledAnalyses)) {
            $analysisDefinitions = AnalysisDefinition::all('slug');
            $slugs = [];
            foreach ($analysisDefinitions as $analysisDefinition) {
                $slugs[] = $analysisDefinition->slug;
            }
            return $slugs;
        }

        return $enabledAnalyses;
    }

    public function setEnabledAnalyses($enabledAnalyses) {
        $this->enabled_analyses = json_encode($enabledAnalyses);
    }

    public function getEnabledAnalysesWithStatus() {
        $enabledAnalyses = $this->getEnabledAnalyses();
        $analysisStatuses = [];
        foreach ($enabledAnalyses as $enabledAnalysis) {
            $analysisStatuses[$enabledAnalysis] = $this->getAnalysisStatus($enabledAnalysis);
        }

        return $analysisStatuses;
    }

    private function getAnalysisStatus($analysisSlug) {
        $analysisDefinition = AnalysisDefinition::where('slug', $analysisSlug)->first();
        $samplesIds = $this->getSamplesIdsArr();
        $analyses = Analysis::whereIn('sample_id', $samplesIds)
            ->where('enabled', true)
            ->where('analysis_definition_id', $analysisDefinition->id)
            ->get();

        if ($analyses->isEmpty()) {
            return Analysis::STATUS_NOT_STARTED;
        }

        $analysisComplete = true;
        foreach ($analyses as $analysis) {
            if ($analysis->getAttributeValueOrNull('completion__analysis_complete') !== 'completed') {
                $analysisComplete = false;
            }
        }

        if ($analysisComplete) {
            return Analysis::STATUS_COMPLETE;
        }

        return Analysis::STATUS_IN_PROGRESS;
    }

    public function areAllEnabledAnalysesCompleted() {
        $enabledAnalysesWithStatus = $this->getEnabledAnalysesWithStatus();

        $areAllAnalysesCompleted = true;
        foreach ($enabledAnalysesWithStatus as $analysis => $status) {
            if ($status !== Analysis::STATUS_COMPLETE) {
                $areAllAnalysesCompleted = false;
            }
        }

        if ($areAllAnalysesCompleted && !empty($enabledAnalysesWithStatus)) {
            return true;
        }

        return false;
    }
    
    public function getSamplesSummary() {
        $query = DB::table('samples')
            ->leftJoin('analysis', 'analysis.sample_id', '=', 'samples.id')
            ->leftJoin('analysis_definitions', 'analysis.analysis_definition_id', '=', 'analysis_definitions.id')
            ->leftJoin(DB::raw(
                    '(SELECT samples.id AS sample_id, count(*) as analyses_completed
                        FROM samples
                        LEFT JOIN analysis ON analysis.sample_id = samples.id
                        LEFT JOIN analysis_attributes ON analysis.id = analysis_attributes.analysis_id AND analysis_attributes.`attribute`="completion__analysis_complete"
                        WHERE 
                        samples.samples_package_id=' . ((int) $this->id) . '
                        AND samples.deleted = 0
                        AND analysis.enabled=1
                        AND analysis_attributes.value="completed"
                        GROUP BY samples.id
                    ) AS analyses_completed'
                ), function($join) {
                $join->on('samples.id', '=', 'analyses_completed.sample_id');
            })
            ->select('samples.id')
            ->selectRaw('MAX(samples.weight) as weight')
            ->selectRaw('GROUP_CONCAT(analysis_definitions.slug) as analyses')
            ->selectRaw('IF (COUNT(analysis_definitions.slug) = MAX(analyses_completed.analyses_completed), "✔", "" ) AS assigned_analyses_completed')
            ->where('samples.samples_package_id', '=', $this->id)
            ->where('samples.deleted', '=', 0)
            ->groupBy('samples.id');
        
        return $query->get();
    }
    
    public function getSamplesWeightsArr() {
        $query = DB::table('samples')
            ->select('samples.id', 'samples.weight')
            ->where('samples.samples_package_id', '=', $this->id)
            ->where('samples.deleted', '=', 0);
        $samplesWeights = $query->get();
        $samplesWeightsArr = [];
        foreach ($samplesWeights as $samplesWeight) {
            $samplesWeightsArr[$samplesWeight->id] = $samplesWeight->weight;
        }
        return $samplesWeightsArr;
    }
}

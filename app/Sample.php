<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SamplesPackage;
use App\SamplesList;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\AnalysisDefinition;
use Illuminate\Support\Facades\DB;
use App\Analysis;

class Sample extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = ['weight'];

    public function analyses()
    {
        return $this->hasMany('App\Analysis');
    }

    public function package()
    {
        return $this->belongsTo('App\SamplesPackage', 'samples_package_id');
    }

    public function getAnalysesDefinitionSlugs() {
        $analysesDefinitionSlugsArr = [];
        foreach($this->getEnabledAnalyses() as $analysis) {
            $analysesDefinitionSlugsArr[] = $analysis->definition->slug;
        }

        sort($analysesDefinitionSlugsArr);

        return implode(',', $analysesDefinitionSlugsArr);
    }

    public function getPackageManifacturingTime() {
        $this->package()->get();
        return $this->package()->manifacturing_time;
    }

    public static function findSamplesListBySampleId($sampleId) {
        $sample = self::find($sampleId);
        if(!$sample) {
            return false;
        }
        $package = SamplesPackage::find($sample->samples_package_id);
        if(!$package) {
            return false;
        }

        return SamplesList::find($package->samples_list_id);
    }

    public static function findSamplesPackageBySampleId($sampleId) {
        $sample = self::find($sampleId);
        if(!$sample) {
            return false;
        }

        return $package = SamplesPackage::find($sample->samples_package_id);
    }

    public function getSamplesList() {
        return self::findSamplesListBySampleId($this->id);
    }

    public static function deleteSamplesInPackage($samplesIds, $samplesPackage) {
        Sample::whereIn('id', $samplesIds)
            ->where('samples_package_id', '=', $samplesPackage->id)
            ->update(['deleted' => 1]);
    }

    public function getEnabledAnalyses() {
        return $this->analyses()->where('enabled', '=', true)->get();
    }

    public function updateAnalyses($analysesString) {
        $analysesSlugsArr = explode(',', $analysesString);

        $analysesRemoved = [];
        $analysesAdded = [];
        $analysesCurrent = [];

        foreach($this->getEnabledAnalyses() as $analysis) {
            $slug = $analysis->definition->slug;
            $analysesCurrent[] = $slug;

            if (!in_array($slug, $analysesSlugsArr)) {
                $analysesRemoved[] = $slug;
            }
        }

        if (empty($analysesRemoved)
            && count($analysesSlugsArr) == count($analysesCurrent)) {
            return;
        }

        foreach($analysesSlugsArr as $slug) {
            if (!in_array($slug, $analysesCurrent)) {
                $analysesAdded[] = $slug;
            }
        }


        $analysesDefinitions = AnalysisDefinition::all();
        $analysesDefinitionsSlugsToIds = [];
        $analysesDefinitionsSlugs = [];
        $analysesDefinitionsSlugsToAnalysisDefinition = [];
        foreach ($analysesDefinitions as $analysesDefinition) {
            $analysesDefinitionsSlugsToIds[$analysesDefinition->slug] = $analysesDefinition->id;
            $analysesDefinitionsSlugs[] = $analysesDefinition->slug;
            $analysesDefinitionsSlugsToAnalysisDefinition[$analysesDefinition->slug] = $analysesDefinition;
        }

        foreach ($analysesRemoved as $analysisSlug) {
            $analysis = Analysis::where('sample_id', '=', $this->id)
                ->where('analysis_definition_id', '=', $analysesDefinitionsSlugsToIds[$analysisSlug])
                ->first();

            if ($analysis) {
                $analysis->enabled = false;
                $analysis->update();
            }
        }

        foreach ($analysesAdded as $analysisSlug) {
            if (!in_array($analysisSlug, $analysesDefinitionsSlugs)) {
                continue;
            }

            $analysis = Analysis::where('sample_id', '=', $this->id)
                ->where('analysis_definition_id', '=', $analysesDefinitionsSlugsToIds[$analysisSlug])
                ->first();

            if ($analysis) {
                $analysis->enabled = true;
                $analysis->update();
            } else {
                $sampleAnalysis = new Analysis();
                $sampleAnalysis->sample_id = $this->id;
                $sampleAnalysis->definition()->associate($analysesDefinitionsSlugsToAnalysisDefinition[$analysisSlug]);
                $sampleAnalysis->wip = 1;
                $sampleAnalysis->save();
            }
        }
    }

    public function getAssignedAnalysesCompletedString() {
        $analysisIds = DB::table('analysis')
            ->select('analysis.id')
            ->where('analysis.enabled', '=', 1)
            ->where('analysis.sample_id', '=', $this->id)
            ->pluck('id')->toArray();
        
        $query = DB::table('analysis')
            ->leftJoin('analysis_attributes', 'analysis.id', '=', 'analysis_attributes.analysis_id')
            ->select('analysis_attributes.value')
            ->where('analysis.enabled', '=', 1)
            ->where('analysis.sample_id', '=', $this->id)
            ->where('analysis_attributes.attribute', '=', 'completion__analysis_complete');
        
        $values = $query->pluck('value')->toArray();
        
        if (empty($values) || count($analysisIds) != count($values)) {
            return '';
        }
        
        foreach ($values as $value) {
            if ($value !== 'completed') {
                return '';
            }
        }
        
        return '✔';
    }
}

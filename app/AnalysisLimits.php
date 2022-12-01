<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\ParentNameHelper;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Support\Facades\DB;

class AnalysisLimits extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = [
        'type', 'analysis_slug', 'definition',
    ];

    const NAMES_SEPARATOR = '__';

    public function getDefinitionArray() : array {
        return json_decode($this->definition, true);
    }

    public static function getLimitsJsonByTypeAndAnalysis($type, $analysis) {
        $analysisLimits = self::where('type', '=', $type)
            ->where('analysis_slug', '=', $analysis)
            ->first();

        if(!$analysisLimits) {
            return "{}";
        }

        return $analysisLimits->definition;
    }

    public static function getDiaperWeightsLimitsJsonByTypeAndUsedMaterials($type, $samplesList) {
        if(!$samplesList->qa_journal) {
            return '{}';
        }

        $analysisLimits = self::where('type', '=', $type)
            ->where('analysis_slug', '=', 'diaper_weights')
            ->first();

        if(!$analysisLimits) {
            return "{}";
        }

        $analysisLimitsArr = json_decode($analysisLimits->definition);

        foreach($samplesList->qa_journal->ingredients as $qaJournalIngredient) {
            $ingredientName = $qaJournalIngredient->ingredient->name;
            $optionName = $qaJournalIngredient->option->name;

            foreach($analysisLimitsArr as $ingredient => $searchLimitsArr) {
                if(strpos($ingredientName, $ingredient) !== false ) {
                    foreach($searchLimitsArr as $searchString => $limitsArr) {
                        if(strpos($optionName, $searchString) !== false ) {
                            return json_encode($limitsArr);
                        }
                    }
                }
            }
        }

        return '{}';
    }

    public static function getOptions() {
        $options = self::select('type')->distinct()->get()->toArray();
        $optionsArr = [];
        foreach($options as $tmpArr) {
            $optionsArr[] = $tmpArr['type'];
        }

        return $optionsArr;
    }
}

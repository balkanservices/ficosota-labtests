<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\FormulaHelper;
use App\Helpers\ParentNameHelper;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Support\Facades\Lang;

class Analysis extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = ['sample_id', 'analysis_definition_id',
//        'infusion_1', 'infusion_2', 'infusion_3',
//		'absorption_1', 'absorption_2', 'absorption_3',
//		'wicking_rate_1', 'wicking_rate_2', 'wicking_rate_3',
		'wip'];

	protected $table = 'analysis';

    const STATUS_COMPLETE = 'complete';
    const STATUS_IN_PROGRESS = 'in-progress';
    const STATUS_NOT_STARTED = 'not-started';

    /**
     * Get the attributes for the analysis.
     */
    public function attributes()
    {
        return $this->hasMany('App\AnalysisAttribute');
    }

    public function definition()
    {
        return $this->belongsTo('App\AnalysisDefinition', 'analysis_definition_id');
    }

    public function sample()
    {
        return $this->belongsTo('App\Sample');
    }

    public function getAttributesArray($table = null) : array {
        $attributesArr = $this->_getInitializedAttributesArray($table);
        if($table) {
            $attributes = $this->attributes()
                ->where('attribute', 'like', $table . '%')
                ->get();
        } else {
            $attributes = $this->attributes()->get();
        }

        foreach($attributes as $attribute) {
            $attributesArr[$attribute->attribute] = $attribute->value;
        }

        return $attributesArr;
    }

    private function _getEmptyAttributesArray($table = null) : array {
        $attributesArr = [];

        $attributeNamesArr = $this->definition->getAttributeNamesFlat();

        foreach($attributeNamesArr as $attributeName) {
            if(!$table || strpos($attributeName, $table) === 0) {
                $attributesArr[$attributeName] = '';
            }
        }

        return $attributesArr;
    }

    private function _getInitializedAttributesArray($table = null) : array {
        $attributesArr = $this->_getEmptyAttributesArray($table);

        $attributesArr['id'] = $this->id;
//        $attributesArr['weight'] = $this->sample->weight;

        if(!$table) {
            foreach($this->fillable as $attribute) {
                $attributesArr[$attribute] = $this->{$attribute};
            }
        }

        return $attributesArr;
    }

    public function setAttributeValue($key, $value) {
        $analysisAttribute = $this->attributes()->where('attribute', $key)->first();

        $attributeProperties = $this->definition->getAttributesPropertiesFlat();

        if($value == null) {
            $value = '';
        }

        if ($value !== '' && isset($attributeProperties[$key])
            && isset($attributeProperties[$key]['type'])
            && $attributeProperties[$key]['type'] == 'number') {

            $value = str_replace(',', '.', $value);

            if (!is_numeric($value)) {
                throw new \Exception(Lang::trans('samples_list.error.value_should_be_numeric'));
            }

            $value = (float) $value;

            if (isset($attributeProperties[$key]['step'])) {
                $value = round($value, strlen($attributeProperties[$key]['step']) - 2);
            }
        }

        if(empty($analysisAttribute)) {
            $analysisAttribute = new AnalysisAttribute();
            $analysisAttribute->attribute = $key;
            $analysisAttribute->value = $value;
            $this->attributes()->save($analysisAttribute);
        } else {
            $analysisAttribute->value = $value;
            $analysisAttribute->save();
        }

        return $this->_applyFormula($key);
    }

    private function _applyFormula($attributeName) {
        $formulaFields = $this->definition->getFormulasForAttributeName($attributeName);

        $attributeParentName = ParentNameHelper::getParentName($attributeName);

        $parentsToRefresh = [];

        foreach($formulaFields as $formulaFieldArr) {
            //Get attributes everytime, so if updating a formula updates another formula, the value is not lost
            $attributesArr = $this->getAttributesArray();
            
            $calculatedValue = FormulaHelper::calculateValue($formulaFieldArr, $attributesArr);
            $this->setAttributeValue($formulaFieldArr['name'], $calculatedValue);

            $formulaFieldParentName = ParentNameHelper::getParentName($formulaFieldArr['name']);
            if($attributeParentName !== $formulaFieldParentName) {
                $parentsToRefresh[] = $formulaFieldParentName;
            }
        }

        return $parentsToRefresh;
    }

    public function setMultipleAttributeValues(array $attributeValues) {
        $parentsToRefresh = [];
        foreach($attributeValues as $key => $value) {
            $parentsToRefresh = array_merge($parentsToRefresh, $this->setAttributeValue($key, $value));
        }

        return array_unique($parentsToRefresh);
    }

    public function duplicate() {
        try {
            DB::beginTransaction();
            $newAnalysis = $this->replicate();
            $newAnalysis->save();

            $attributes = $this->attributes()->get();
            foreach($attributes as $attribute) {
                $newAttribute = $attribute->replicate();
                $newAttribute->analysis_id = $newAnalysis->id;
                $newAttribute->save();

                $newAnalysis->attributes()->save($newAttribute);
            }

            DB::commit();
            return $newAnalysis;

        } catch( \Exception $e ) {
            DB::rollBack();
            return false;
        }
    }

    public function getAttributeValueOrNull($key) {
        $analysisAttribute = $this->attributes()->where('attribute', $key)->first();

        if (empty($analysisAttribute)) {
            return null;
        }

        return $analysisAttribute->value;
    }

    public function getMannequinPositionCssClass() {
        if ($this->definition->slug == 'absorbtion_before_leakage') {
            $position = $this->getAttributeValueOrNull('table_1__mannequin_position');
            if (in_array($position, ['standing', 'sideways'])) {
                return 'abl_' . $position;
            } elseif ($position == 'belly/back') {
                return 'abl_belly_back';
            }
        }

        return null;
    }
}

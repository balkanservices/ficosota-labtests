<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\ParentNameHelper;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Support\Facades\Lang;

class AnalysisDefinition extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = [
        'name', 'definition', 'slug',
    ];

    const NAMES_SEPARATOR = '__';

    public function getDefinitionArray() : array {
        return json_decode($this->definition, true);
    }

    public function getAttributeNames() : array {
        $attributesArr = $this->getDefinitionArray();

        $attributeNamesArr = $this->_extractNames($attributesArr);

        return $attributeNamesArr;
    }

    public function getAttributeNamesFlat() : array {
        $attributeNamesArr = $this->getAttributeNames();
        $attributeNamesFlatArr = [];

        array_walk_recursive($attributeNamesArr, function($value) use (&$attributeNamesFlatArr) { $attributeNamesFlatArr[] = $value; });

        return $attributeNamesFlatArr;
    }

    public function getFormulasForAttributeName($attributeName) : array {
        $attributesArr = $this->getDefinitionArray();
        $formulaFieldsFlatArr = [];

        $this->_fillFormulas($formulaFieldsFlatArr, $attributesArr, $attributeName);

        return $formulaFieldsFlatArr;
    }

    private function _fillFormulas(&$formulaFieldsFlatArr, $attributesArr, $attributeName, $parentName = null) {
        foreach($attributesArr as $attribute) {
            $fullAttributeName = ParentNameHelper::addParentName($attribute['name'], $parentName);

            if($attribute['type'] === 'group') {
                $this->_fillFormulas($formulaFieldsFlatArr, $attribute['group_fields'], $attributeName, $fullAttributeName);
            } elseif($attribute['type'] === 'tab_group') {
                $this->_fillFormulas($formulaFieldsFlatArr, $attribute['group_fields'], $attributeName, $fullAttributeName);
            } elseif($attribute['type'] === 'formula') {
                foreach($attribute['formula_fields'] as $index => $formulaField) {
                    if(!is_numeric($formulaField) && strpos($formulaField, self::NAMES_SEPARATOR) === false) {
                        $attribute['formula_fields'][$index] = ParentNameHelper::addParentName($formulaField, $parentName);
                    }
                }

                if(strpos($attribute['name'], self::NAMES_SEPARATOR) === false) {
                    $attribute['name'] = ParentNameHelper::addParentName($attribute['name'], $parentName);
                }

                if(in_array($attributeName, $attribute['formula_fields'])) {
                    $formulaFieldsFlatArr[] = $attribute;
                }
            }
        }
    }

    private function _extractNames($attributesArr, $parentName = null) {
        $attributeNamesArr = [];

        foreach($attributesArr as $attributeArr) {

            $extractedAttributeNames = $this->_extractAttributeNames($attributeArr, $parentName);

            if($parentName && !isset($attributeNamesArr[$parentName])) {
                $attributeNamesArr[$parentName] = [];
            }

            if($parentName) {
                $this->_mergeAttributeNames($attributeNamesArr[$parentName], $extractedAttributeNames);
            } else {
                $this->_mergeAttributeNames($attributeNamesArr, $extractedAttributeNames);
            }
        }

        return $attributeNamesArr;
    }

    private function _extractAttributeNames($attribute, $parentName = null) {
        $fullAttributeName = ParentNameHelper::addParentName($attribute['name'], $parentName);

        if($attribute['type'] === 'group') {
            return $this->_extractNames($attribute['group_fields'], $fullAttributeName);
        }elseif($attribute['type'] === 'tab_group') {
            return $this->_extractNames($attribute['group_fields'], $fullAttributeName);
        } else {
            return $fullAttributeName;
        }
    }

    private function _mergeAttributeNames(&$targetArr, $newValues) {
        if(is_array($newValues)) {
            $targetArr = array_merge($targetArr, $newValues);
        } else {
            $targetArr[] =  $newValues;
        }
    }

    public function getAttributesPropertiesFlat() : array {
        $attributesArr = $this->getDefinitionArray();
        $attributeNamesFlatArr = $this->getAttributeNamesFlat();
        $attributePropertiesFlatArr = array_flip($attributeNamesFlatArr);

        $this->_fillAttributesProperties($attributePropertiesFlatArr, $attributesArr);

        return $attributePropertiesFlatArr;
    }

    private function _fillAttributesProperties(&$attributePropertiesFlatArr, $attributesArr, $parentName = null) {
        foreach($attributesArr as $attribute) {
            $fullAttributeName = ParentNameHelper::addParentName($attribute['name'], $parentName);

            if($attribute['type'] === 'group') {
                $this->_fillAttributesProperties($attributePropertiesFlatArr, $attribute['group_fields'], $fullAttributeName);
            } elseif($attribute['type'] === 'tab_group') {
                $this->_fillAttributesProperties($attributePropertiesFlatArr, $attribute['group_fields'], $fullAttributeName);
            } else {
                $attributePropertiesFlatArr[$fullAttributeName] = $attribute['properties'];
            }

        }
    }

    public function getDisabledAttributeNames() {
        $attributesArr = $this->getDefinitionArray();
        $disabledAttributesFlatArr = [];

        $this->_fillDisabledAttributes($disabledAttributesFlatArr, $attributesArr);

        return $disabledAttributesFlatArr;
    }

    private function _fillDisabledAttributes(&$disabledAttributesFlatArr, $attributesArr, $parentName = null) {
        foreach($attributesArr as $attribute) {
            $fullAttributeName = ParentNameHelper::addParentName($attribute['name'], $parentName);

            if($attribute['type'] === 'group') {
                $this->_fillDisabledAttributes($disabledAttributesFlatArr, $attribute['group_fields'], $fullAttributeName);
            }elseif($attribute['type'] === 'tab_group') {
                $this->_fillDisabledAttributes($disabledAttributesFlatArr, $attribute['group_fields'], $fullAttributeName);
            } else if($attribute['type'] === 'formula') {
                $disabledAttributesFlatArr[] = $fullAttributeName;
            }

        }
    }

    public static function getNamesArr() {
        $analysisDefinitions = self::all();
        $namesArr = [];
        foreach ($analysisDefinitions as $analysisDefinition) {
            $namesArr[$analysisDefinition->slug] = Lang::trans('samples_list.analyses_types.' . $analysisDefinition->slug);
        }

        return $namesArr;
    }

    public static function getSlugsArr() {
        $analysisDefinitions = self::all('slug', 'id');
        $slugsArr = [];
        foreach ($analysisDefinitions as $analysisDefinition) {
            $slugsArr[$analysisDefinition->slug] = $analysisDefinition->id;
        }

        return $slugsArr;
    }

    public function getFormulaFields() : array {
        $attributesArr = $this->getDefinitionArray();
        $formulaFieldsFlatArr = [];

        $this->_fillFormulaFields($formulaFieldsFlatArr, $attributesArr);

        return $formulaFieldsFlatArr;
    }

    private function _fillFormulaFields(&$formulaFieldsFlatArr, $attributesArr, $parentName = null) {
        foreach($attributesArr as $attribute) {
            $fullAttributeName = ParentNameHelper::addParentName($attribute['name'], $parentName);

            if($attribute['type'] === 'group') {
                $this->_fillFormulaFields($formulaFieldsFlatArr, $attribute['group_fields'], $fullAttributeName);
            } elseif($attribute['type'] === 'tab_group') {
                $this->_fillFormulaFields($formulaFieldsFlatArr, $attribute['group_fields'], $fullAttributeName);
            } elseif($attribute['type'] === 'formula') {
                foreach($attribute['formula_fields'] as $index => $formulaField) {
                    if(!is_numeric($formulaField) && strpos($formulaField, self::NAMES_SEPARATOR) === false) {
                        $attribute['formula_fields'][$index] = ParentNameHelper::addParentName($formulaField, $parentName);
                    }
                }

                if(strpos($attribute['name'], self::NAMES_SEPARATOR) === false) {
                    $attribute['name'] = ParentNameHelper::addParentName($attribute['name'], $parentName);
                }
                $formulaFieldsFlatArr[] = $attribute;
            }
        }
    }

    public function getEmptyAttributesArray(string $table = null) : array {
        $attributesArr = [];

        $attributeNamesArr = $this->getAttributeNamesFlat();

        foreach($attributeNamesArr as $attributeName) {
            if(!$table || strpos($attributeName, $table) === 0) {
                $attributesArr[$attributeName] = '';
            }
        }

        return $attributesArr;
    }
}

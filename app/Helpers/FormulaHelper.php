<?php

namespace App\Helpers;

class FormulaHelper
{
    public static function calculateValue(array $formulaField, array $attributes) {
		switch($formulaField['formula']) {
            case "SUM":
                return self::_calculateSum($formulaField, $attributes);
            case "DIV":
                return self::_calculateDiv($formulaField, $attributes);
            case "SUB":
                return self::_calculateSub($formulaField, $attributes);
            case "PERCENTAGE":
                return self::_calculatePercentage($formulaField, $attributes);
            case "SAP_FLUFF_PULP":
                return self::_calculateSapFluffPulp($formulaField, $attributes);
            case "SUB_MULTIPLE":
                return self::_calculateSubMultiple($formulaField, $attributes);
            case "AVG":
                return self::_calculateAVG($formulaField, $attributes);
            case "SUB_DIV":
                return self::_calculateSubDiv($formulaField, $attributes);
        }
	}

    private static function _calculateSum(array $formulaFieldArr, array $attributesArr) {
        $total = 0;
		foreach($formulaFieldArr['formula_fields'] as $attributeName) {
			if(!isset($attributesArr[$attributeName]) || $attributesArr[$attributeName] === null || $attributesArr[$attributeName] === '') {
				$total = null;
				break;
			}

			$total += $attributesArr[$attributeName];
		}

		return $total;
    }

    private static function _calculateDiv(array $formulaFieldArr, array $attributesArr) {

        $dividend = self::_getComponentValue($formulaFieldArr['formula_fields']['dividend'], $attributesArr);
        $divisor = self::_getComponentValue($formulaFieldArr['formula_fields']['divisor'], $attributesArr);

        //dividend can be zero, divisor cannot
		if($dividend == null || empty($divisor)) {
            return null;
        }

        return round($dividend / $divisor, 2);
    }

    private static function _getComponentValue($component, array $attributesArr){
        $componentValue = null;
        if(is_numeric($component)) {
            $componentValue = $component;
        } else {
            if(!isset($attributesArr[$component])) {
                return null;
            }
            $componentValue = $attributesArr[$component];
        }

        return $componentValue;
    }

    private static function _calculateSub(array $formulaFieldArr, array $attributesArr) {
		foreach($formulaFieldArr['formula_fields'] as $attributeName) {
			if(!isset($attributesArr[$attributeName]) || $attributesArr[$attributeName] === null || $attributesArr[$attributeName] === '') {
				return null;
			}
		}

        return $attributesArr[$formulaFieldArr['formula_fields'][0]] -
                $attributesArr[$formulaFieldArr['formula_fields'][1]];
    }

    private static function _calculatePercentage(array $formulaFieldArr, array $attributesArr) {

        $total = self::_getComponentValue($formulaFieldArr['formula_fields']['total'], $attributesArr);
        $part = self::_getComponentValue($formulaFieldArr['formula_fields']['part'], $attributesArr);

		if(empty($total) || empty($part)) {
            return 0;
        }

        return round(($part / $total) * 100, 2);
    }

    private static function _calculateSapFluffPulp(array $formulaFieldArr, array $attributesArr) {

        $dividend = self::_getComponentValue($formulaFieldArr['formula_fields']['dividend'], $attributesArr);
        $divisor = self::_getComponentValue($formulaFieldArr['formula_fields']['divisor'], $attributesArr);

        //dividend can be zero, divisor cannot
		if($dividend == null || empty($divisor)) {
            return null;
        }

        return round(($dividend / ($dividend+$divisor)) * 100, 2);
    }

    private static function _calculateSubMultiple(array $formulaFieldArr, array $attributesArr) {
		foreach($formulaFieldArr['formula_fields'] as $attributeName) {
			if(!isset($attributesArr[$attributeName]) || $attributesArr[$attributeName] === null || $attributesArr[$attributeName] === '') {
				return null;
			}
		}

        $total = $attributesArr[$formulaFieldArr['formula_fields'][0]];

        for($i = 1; $i < count($formulaFieldArr['formula_fields']); $i++) {
            $total -= $attributesArr[$formulaFieldArr['formula_fields'][$i]];
        }

        return $total;
    }

    private static function _calculateAVG(array $formulaFieldArr, array $attributesArr) {
        $total = 0;
        $count = 0;
		foreach($formulaFieldArr['formula_fields'] as $attributeName) {
			if(!isset($attributesArr[$attributeName]) || $attributesArr[$attributeName] === null || $attributesArr[$attributeName] === '') {
				$total = null;
				break;
			}

			$total += $attributesArr[$attributeName];
            $count++;
		}

        if (empty($count)) {
            return null;
        }

        $roundTo = 2;

        if (isset($formulaFieldArr['properties'])
            && isset($formulaFieldArr['properties']['step'])) {
            $roundTo = strlen($formulaFieldArr['properties']['step'] - 2);
        }

		return round($total / $count, $roundTo);
    }

    private static function _calculateSubDiv(array $formulaFieldArr, array $attributesArr) {

        $first = self::_getComponentValue($formulaFieldArr['formula_fields']['first'], $attributesArr);
        $second = self::_getComponentValue($formulaFieldArr['formula_fields']['second'], $attributesArr);
        $divisor = self::_getComponentValue($formulaFieldArr['formula_fields']['divisor'], $attributesArr);

        //dividend can be zero, divisor cannot
		if($first == null || $second == null || empty($divisor)) {
            return null;
        }

        return round(($first - $second) / $divisor, 2);
    }
}

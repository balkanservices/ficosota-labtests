<?php

namespace App\Helpers;

class ParentNameHelper
{
    const SEPARATOR = '__';
    
    public function addParentNames($attributeName, $parentName) {
		if($parentName) {
            $attributeName = $parentName . self::SEPARATOR . $attributeName;
        } 
        
        return $attributeName;
	}
    
    public static function addParentName($attributeName, $parentName) {
		if($parentName) {
            $attributeName = $parentName . self::SEPARATOR . $attributeName;
        } 
        
        return $attributeName;
	}
    
    public static function getParentName($attributeName) {
        $nameArr = explode(self::SEPARATOR, $attributeName);
        if(count($nameArr) > 1) {
            array_pop($nameArr);
            return implode(self::SEPARATOR, $nameArr);
        }
		
        return $attributeName;
	}
}

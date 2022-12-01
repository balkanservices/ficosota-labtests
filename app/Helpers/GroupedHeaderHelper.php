<?php

namespace App\Helpers;

class GroupedHeaderHelper
{
    public static function getAttributeGroupedHeader($attribute, $groupedHeaderArr) {
        foreach($groupedHeaderArr as $groupedHeader) {
            if(in_array($attribute['name'], $groupedHeader['fields'])) {
                return $groupedHeader;
            }
        }
        
        return false;
	}
}

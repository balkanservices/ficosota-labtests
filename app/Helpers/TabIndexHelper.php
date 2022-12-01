<?php

namespace App\Helpers;

class TabIndexHelper
{
    static $tabIndex = 1;
    public function getIncrementedTabIndex() {
		return self::$tabIndex++;
	}
}

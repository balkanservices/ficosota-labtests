<?php

namespace App\Helpers;

use App\AnalysisLimits;

class AnalysisLimitsSeederHelper
{
    public static function seedOrUpdate($limitsFile, $type = null) {
		$limitsJsonFile = __DIR__ . '/../../database/seeds/analysis_limits_jsons/' . $limitsFile . '.json';
        $limitsArr = json_decode(file_get_contents($limitsJsonFile), true);

        $limitsArr['definition'] = json_encode($limitsArr['definition']);

        $type = $type ? : $limitsArr['type'];

        $limits = AnalysisLimits::
            where('type', '=', $type)
            ->where('analysis_slug', '=', $limitsArr['analysis_slug'])
            ->first();


        if($limits) {
            $limits->type = $type;
            $limits->analysis_slug = $limitsArr['analysis_slug'];
            $limits->definition = $limitsArr['definition'];
            $limits->save();
        } else {
            $limits = new AnalysisLimits($limitsArr);
            $limits->type = $type;
            $limits->save();
        }
	}
}

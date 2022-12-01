<?php

namespace App\Helpers;

use Barryvdh\TranslationManager\Models\Translation;

class LanguageLineHelper
{
    /**
     * Kept for migrations compatability with the old interface
     */
    public static function updateOrCreateLanguageLine($group, $key, $text, $locale = 'bg') {
		$languageLine = Translation::
                where('group', '=', $group)
                ->where('key', '=', $key)
                ->where('locale', '=', $locale)
                ->first();

        if ($languageLine) {
            $languageLine->value = $text[$locale];
            $languageLine->save();
        } else {
            Translation::create([
                'locale' => $locale,
                'group' => $group,
                'key' => $key,
                'value' => $text[$locale],
            ]);
        }
	}
}

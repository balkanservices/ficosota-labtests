<?php

use Illuminate\Database\Seeder;
use App\Helpers\LanguageLineHelper;

class LanguageLinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $translationFiles = [
			'home', 'qa_journals', 'recipes', 'samples_list', 'products'
        ];

        foreach($translationFiles as $translationFile) {
            $translationArr = include __DIR__ . '/translations/' . $translationFile . '.php';
            $this->_addTranslations($translationArr, $translationFile);
        }
    }

    private function _addTranslations($translationArr, $group, $parentKey = null) {
        foreach($translationArr as $key => $value) {
            if($parentKey) {
                $fullKey = $parentKey . '.' . $key;
            } else {
                $fullKey = $key;
            }

            if(is_array($value)) {
                $this->_addTranslations($value, $group, $fullKey);
            } else {
                LanguageLineHelper::updateOrCreateLanguageLine(
                    $group,
                    $fullKey,
                    ['bg' => $value]
                );
            }
        }
    }
}

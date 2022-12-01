<?php

namespace App\Helpers;

use App\AnalysisDefinition;
use App\Helpers\ParentNameHelper;
use App\Helpers\LanguageLineHelper;

class AnalysisDefinitionSeederHelper
{
    public static function seedOrUpdate($definitionFile) {
		$definitionJsonFile = __DIR__ . '/../../database/seeds/analysis_definition_jsons/' . $definitionFile . '.json';
        $definitionArr = json_decode(file_get_contents($definitionJsonFile), true);

        LanguageLineHelper::updateOrCreateLanguageLine(
                'samples_list', 
                'analyses_types.' . $definitionArr['slug'], 
                ['bg' => $definitionArr['name']]
        );

        LanguageLineHelper::updateOrCreateLanguageLine(
                'samples_list', 
                'analyses_types_short.' . $definitionArr['slug'],
                ['bg' => $definitionArr['name_short']]
        );

        unset($definitionArr['name_short']);

        $definitionArr['definition'] = json_encode($definitionArr['definition']);

        $definition = AnalysisDefinition::where('slug', '=', $definitionArr['slug'])->first();

        if($definition) {
            $definition->name = $definitionArr['name'];
            $definition->definition = $definitionArr['definition'];
            $definition->save();
        } else {
            $definition = new AnalysisDefinition($definitionArr);
            $definition->save();
        }

        self::_addTranslations($definition->getDefinitionArray(), $definitionArr['slug']);
	}
    
    private static function _addTranslations($attributesArr, $slug, $parentName = null) {
        foreach($attributesArr as $attribute) {
            $fullAttributeName = ParentNameHelper::addParentName($attribute['name'], $parentName);
            
            if(isset($attribute['label_bg'])) {
                LanguageLineHelper::updateOrCreateLanguageLine(
                    'samples_list',
                    'analysis_fields.' . $slug . '.'. $fullAttributeName,
                    ['bg' => $attribute['label_bg']]
                );
            }
            
            if(isset($attribute['type']) && ($attribute['type'] === 'group' || $attribute['type'] === 'tab_group')) {
                self::_addTranslations($attribute['group_fields'], $slug, $fullAttributeName);
                
                if(isset($attribute['grouped_headers'])) {
                    self::_addTranslations($attribute['grouped_headers'], $slug, $fullAttributeName);
                }
            } 
        }
    }
}

<?php

use Illuminate\Database\Seeder;

use App\QaJournal;
use App\QaJournalIngredient;

class QaJournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $qaJournals = [
			[
				'recipe_id'	=>	1,
				'batch_number'	=>	'1234',
				'ingredients'	=>	[
					[
						'ingredient_id'	=>	1,
						'option_id'	=>	1,
					],
					[
						'ingredient_id'	=>	2,
						'option_id'	=>	5,
					],
					[
						'ingredient_id'	=>	3,
						'option_id'	=>	9,
					],
					[
						'ingredient_id'	=>	4,
						'option_id'	=>	12,
					],
				]
			]
		];
		
		foreach($qaJournals as $qaJournalArr) {
			$ingredients = $qaJournalArr['ingredients'];
			unset($qaJournalArr['ingredients']);
			
			$qaJournal = new QaJournal($qaJournalArr);
			$qaJournal->save();
			
			foreach($ingredients as $ingredientArr) {
				$ingredient = new QaJournalIngredient($ingredientArr);
				$ingredient->qa_journal_id = $qaJournal->id;
				$ingredient->save();
			}
		}
    }
}

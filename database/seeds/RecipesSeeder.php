<?php

use Illuminate\Database\Seeder;

use App\Recipe;
use App\RecipeIngredient;
use App\RecipeIngredientOption;

class RecipesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$recipes = [
			[
                'product_id' => 1,
				'name'	=>	'PUFIES MAXI Baby Art',
				'comment'	=>	'Всички разходни норми влизат в сила след като бъдат проверени от производството!!!
N.B.! В разходната норма за всички материали с дължина на пелената е заложена дължина 480mm. Изключение – долен слой, заложен Pitch size.
При складова ревизия и установяване на разлика ≥2% за всички материали, да се сигнализира от производството на R&D чрез e-mail.',
				'ingredients'	=>	[
					[
						'name'	=>	'Central Top Sheet',
						'options'	=>	[
							[
								'name'	=>	'PPSB/14/E/S/165',
								'width'	=>	'165',
								'supplier'	=>	'DOUNOR',
								'metric_unit'	=>	'sqm',
								'consumption_per_piece'	=>	'0,079200',
								'comment'	=>	''
							],
							[
								'name'	=>	'PEGATEX S white philic 14gsm',
								'width'	=>	'165',
								'supplier'	=>	'PEGAS',
								'metric_unit'	=>	'sqm',
								'consumption_per_piece'	=>	'0,079200',
								'comment'	=>	''
							],
							[
								'name'	=>	'S1400PIW',
								'width'	=>	'165',
								'supplier'	=>	'UNION',
								'metric_unit'	=>	'sqm',
								'consumption_per_piece'	=>	'0,079200',
								'comment'	=>	''
							],
						],
					],
					[
						'name'	=>	'Leg Cuffs',
						'options'	=>	[
							[
								'name'	=>	'PPSM/17/E/O/S/175',
								'width'	=>	'175',
								'supplier'	=>	'DOUNOR',
								'metric_unit'	=>	'sqm',
								'consumption_per_piece'	=>	'0,084000',
								'comment'	=>	'Одобрени line 6 и line 8; двете линии са идентични'
							],
							[
								'name'	=>	'SMMS Hydrophobic 17gsm',
								'width'	=>	'175',
								'supplier'	=>	'GULSAN',
								'metric_unit'	=>	'sqm',
								'consumption_per_piece'	=>	'0,084000',
								'comment'	=>	''
							],
							[
								'name'	=>	'PEGATEX SMS phobic 17gsm',
								'width'	=>	'175',
								'supplier'	=>	'PEGAS',
								'metric_unit'	=>	'sqm',
								'consumption_per_piece'	=>	'0,084000',
								'comment'	=>	''
							],
							[
								'name'	=>	'D1703PHW',
								'width'	=>	'175',
								'supplier'	=>	'UNION',
								'metric_unit'	=>	'',
								'consumption_per_piece'	=>	'0,084000',
								'comment'	=>	''
							],
						],
					],
					[
						'name'	=>	'ADL',
						'options'	=>	[
							[
								'name'	=>	'A2060 60gsm',
								'width'	=>	'70',
								'supplier'	=>	'TEXSUS',
								'metric_unit'	=>	'sqm',
								'consumption_per_piece'	=>	'0,016450',
								'comment'	=>	''
							],
							[
								'name'	=>	'PES 100 60gsm',
								'width'	=>	'70',
								'supplier'	=>	'Akinal Sentetik (A.S. Nonwovens)',
								'metric_unit'	=>	'sqm',
								'consumption_per_piece'	=>	'0,016450',
								'comment'	=>	''
							],
							[
								'name'	=>	'Dry Web T28 60gsm',
								'width'	=>	'70',
								'supplier'	=>	'LIBELTEX',
								'metric_unit'	=>	'sqm',
								'consumption_per_piece'	=>	'0,016450',
								'comment'	=>	'Резервен материал поради прах по време на производство'
							],
							[
								'name'	=>	'DRY WEB TDL 2, 50gsm',
								'width'	=>	'70',
								'supplier'	=>	'LIBELTEX',
								'metric_unit'	=>	'sqm',
								'consumption_per_piece'	=>	'0,016450',
								'comment'	=>	'Резервен материал поради по-високи RUL and time values'
							],
						],
					],
					[
						'name'	=>	'TOP Core wrapping',
						'options'	=>	[
							[
								'name'	=>	'S1200PIW 12gsm',
								'width'	=>	'135',
								'supplier'	=>	'UNION',
								'metric_unit'	=>	'sqm',
								'consumption_per_piece'	=>	'0,055350',
								'comment'	=>	'Резервен материал – SB, преминаване на лепило. Преди поръчка задължителна консултация с R&D'
							],
							[
								'name'	=>	'PEGATEX SMS hydrophilic 13gsm',
								'width'	=>	'135',
								'supplier'	=>	'PEGAS',
								'metric_unit'	=>	'sqm',
								'consumption_per_piece'	=>	'0,055350',
								'comment'	=>	''
							],
						],
					],
					[
						'name'	=>	'BOTTOM Core wrapping',
						'options'	=>	[
							[
								'name'	=>	'PEGATEX SMS hydrophobic 10gsm',
								'width'	=>	'145',
								'supplier'	=>	'PEGAS',
								'metric_unit'	=>	'sqm',
								'consumption_per_piece'	=>	'0,059450',
								'comment'	=>	''
							],
							[
								'name'	=>	'S1000PHW  10gsm',
								'width'	=>	'145',
								'supplier'	=>	'UNION',
								'metric_unit'	=>	'sqm',
								'consumption_per_piece'	=>	'0,059450',
								'comment'	=>	''
							],
                            [
								'name'	=>	'PPSM/10/E/O/S',
								'width'	=>	'145',
								'supplier'	=>	'DOUNOR',
								'metric_unit'	=>	'sqm',
								'consumption_per_piece'	=>	'0,059450',
								'comment'	=>	''
							],
						],
					],
                    [
						'name'	=>	'Fluff pulp',
						'options'	=>	[
							[
								'name'	=>	'Pana fluff WP675FL –8% moisture',
								'width'	=>	'508',
								'supplier'	=>	'Ekman',
								'metric_unit'	=>	'kg',
								'consumption_per_piece'	=>	'0,011500',
								'comment'	=>	'Резервен материал, поради по-лошия интегритет на сърцевината'
							],
							[
								'name'	=>	'GP cellulose –8% moisture GRADE 4800  / GRADE 4881',
								'width'	=>	'508',
								'supplier'	=>	'GEORGIA PACIFIC',
								'metric_unit'	=>	'kg',
								'consumption_per_piece'	=>	'0,011500',
								'comment'	=>	''
							],
                            [
								'name'	=>	'FR416 KRAFT – 7.5% moisture',
								'width'	=>	'508',
								'supplier'	=>	'WEYERHAUSER',
								'metric_unit'	=>	'kg',
								'consumption_per_piece'	=>	'0,011500',
								'comment'	=>	''
							],
                            [
								'name'	=>	'PN Supersoft Plus – 8%',
								'width'	=>	'508',
								'supplier'	=>	'INTERNATIONAL PAPER',
								'metric_unit'	=>	'kg',
								'consumption_per_piece'	=>	'0,011500',
								'comment'	=>	''
							],
						],
					],
                    [
						'name'	=>	'SAP',
						'options'	=>	[
							[
								'name'	=>	'FAVOR SXM 9155',
								'width'	=>	'508',
								'supplier'	=>	'EVONIK',
								'metric_unit'	=>	'kg',
								'consumption_per_piece'	=>	'0,012500',
								'comment'	=>	'Резервен материал, поради по-лошия интегритет на сърцевината'
							],
							[
								'name'	=>	'HYSORB M7055',
								'width'	=>	'508',
								'supplier'	=>	'BASF',
								'metric_unit'	=>	'kg',
								'consumption_per_piece'	=>	'0,012500',
								'comment'	=>	''
							],
                            [
								'name'	=>	'HYSORB B 7055',
								'width'	=>	'508',
								'supplier'	=>	'BASF',
								'metric_unit'	=>	'kg',
								'consumption_per_piece'	=>	'0,012500',
								'comment'	=>	''
							],
                            [
								'name'	=>	'TAISAP NB 603',
								'width'	=>	'508',
								'supplier'	=>	'FPC',
								'metric_unit'	=>	'kg',
								'consumption_per_piece'	=>	'0,012500',
								'comment'	=>	'Rejected – преминаване на SAP'
							],
                            [
								'name'	=>	'GS 4800',
								'width'	=>	'508',
								'supplier'	=>	'LG Chem',
								'metric_unit'	=>	'kg',
								'consumption_per_piece'	=>	'0,012500',
								'comment'	=>	''
							],
						],
					],
                    [
						'name'	=>	'Textile back sheet',
						'options'	=>	[
							[
								'name'	=>	'BR TBS 28gsm / Nominal pitch size 484.8mm / Acceptable window (480 – 484.8)mm / Pitch size: 480 (+5, -0)mm',
								'width'	=>	'210',
								'supplier'	=>	'GULSAN',
								'metric_unit'	=>	'sqm',
								'consumption_per_piece'	=>	'0,10080',
								'comment'	=>	''
							],
						],
					],
                    [
						'name'	=>	'Landing zone / Cut length: 50mm',
						'options'	=>	[
							[
								'name'	=>	'FT 720 NC',
								'width'	=>	'138',
								'supplier'	=>	'KOESTER',
								'metric_unit'	=>	'sqm',
								'consumption_per_piece'	=>	'0,006900',
								'comment'	=>	''
							],
                            [
								'name'	=>	'A*N29 LOCK-LOOP unprinted',
								'width'	=>	'137',
								'supplier'	=>	'APLIX',
								'metric_unit'	=>	'sqm',
								'consumption_per_piece'	=>	'0,006850',
								'comment'	=>	'До изчерпване на наличното количество.'
							],
						],
					],
                    [
						'name'	=>	'Frontal wings',
						'options'	=>	[
							[
								'name'	=>	'PP Spunbond 40 gsm',
								'width'	=>	'90',
								'supplier'	=>	'KURT KUMAS',
								'metric_unit'	=>	'kg',
								'consumption_per_piece'	=>	'0,000306',
								'comment'	=>	''
							],
                            [
								'name'	=>	'S 4000 PHW',
								'width'	=>	'90',
								'supplier'	=>	'UNION',
								'metric_unit'	=>	'sqm',
								'consumption_per_piece'	=>	'0,007650',
								'comment'	=>	''
							],
                            [
								'name'	=>	'PP SB/40/5/W/O/S/90',
								'width'	=>	' ',
								'supplier'	=>	'DOUNOR',
								'metric_unit'	=>	' ',
								'consumption_per_piece'	=>	'0,007650',
								'comment'	=>	'Одобрен е материалът от line 5'
							],
						],
					],
                    [
						'name'	=>	'Back wings',
						'options'	=>	[
							[
								'name'	=>	'NAS 415 improved bonding (for thermo sealing)',
								'width'	=>	'140',
								'supplier'	=>	'APLIX',
								'metric_unit'	=>	'sqm',
								'consumption_per_piece'	=>	'0,013300',
								'comment'	=>	''
							],
                            [
								'name'	=>	'SEE140S T03',
								'width'	=>	'140',
								'supplier'	=>	'NITTO BENTO',
								'metric_unit'	=>	'sqm',
								'consumption_per_piece'	=>	'0,013300',
								'comment'	=>	'Approved with UV agent inside. NAS 415 is without UV agent.'
							],
						],
					],
                    [
						'name'	=>	'Tape tabs – Left & Right / Cut length: 26mm',
						'options'	=>	[
							[
								'name'	=>	'CP2MY46 H1 / White waved fingerlift',
								'width'	=>	'46',
								'supplier'	=>	'KOESTER',
								'metric_unit'	=>	'sqm',
								'consumption_per_piece'	=>	'0,002609',
								'comment'	=>	'Резервен материал'
							],
                            [
								'name'	=>	'CHL 07286 - yellow',
								'width'	=>	'46',
								'supplier'	=>	'3M',
								'metric_unit'	=>	'sqm',
								'consumption_per_piece'	=>	'0,002609',
								'comment'	=>	''
							],
						],
					],
                    [
						'name'	=>	'Cuff round elastics 2x2 (280% elongation)',
						'options'	=>	[
							[
								'name'	=>	'Round Lycra XA T837',
								'width'	=>	' ',
								'supplier'	=>	'INVISTA',
								'metric_unit'	=>	'kg',
								'consumption_per_piece'	=>	'0,000034',
								'comment'	=>	''
							],
						],
					],
                    [
						'name'	=>	'Leg round elastics 2x3 (280% elongation)',
						'options'	=>	[
							[
								'name'	=>	'Round Lycra XA T837',
								'width'	=>	' ',
								'supplier'	=>	'INVISTA',
								'metric_unit'	=>	'kg',
								'consumption_per_piece'	=>	'0,000052',
								'comment'	=>	''
							],
						],
					],
                    [
						'name'	=>	'Construction hot melt adhesive',
						'options'	=>	[
							[
								'name'	=>	'Technomelt DM Cool 110',
								'width'	=>	' ',
								'supplier'	=>	'Henkel',
								'metric_unit'	=>	'kg',
								'consumption_per_piece'	=>	'0,000650',
								'comment'	=>	''
							],
                            [
								'name'	=>	'Full Care 5100',
								'width'	=>	' ',
								'supplier'	=>	'H.B.Fuller',
								'metric_unit'	=>	'kg',
								'consumption_per_piece'	=>	'0,000654',
								'comment'	=>	''
							],
						],
					],
                    [
						'name'	=>	'Elastics hot melt adhesive',
						'options'	=>	[
							[
								'name'	=>	'Technomelt DM 757 E',
								'width'	=>	' ',
								'supplier'	=>	'Henkel',
								'metric_unit'	=>	'kg',
								'consumption_per_piece'	=>	'0,000108',
								'comment'	=>	''
							],
                            [
								'name'	=>	'Full Care 8200',
								'width'	=>	' ',
								'supplier'	=>	'H.B.Fuller',
								'metric_unit'	=>	'kg',
								'consumption_per_piece'	=>	'0,000094',
								'comment'	=>	''
							],
                            [
								'name'	=>	'NW 1002 ZP=Full Care 8400',
								'width'	=>	' ',
								'supplier'	=>	'H.B.Fuller',
								'metric_unit'	=>	'kg',
								'consumption_per_piece'	=>	'0,000176',
								'comment'	=>	''
							],
						],
					],
				]
			]
		];
		
		foreach($recipes as $recipeArr) {
			$ingredients = $recipeArr['ingredients'];
			unset($recipeArr['ingredients']);
			
			$recipe = new Recipe($recipeArr);
			$recipe->save();
			
			foreach($ingredients as $ingredientArr) {
				$options = $ingredientArr['options'];
				unset($ingredientArr['options']);

				$ingredient = new RecipeIngredient($ingredientArr);
				$ingredient->recipe_id = $recipe->id;
				$ingredient->save();
				
				foreach($options as $optionArr) {
					$option = new RecipeIngredientOption($optionArr);
					$option->recipe_ingredient_id = $ingredient->id;
					$option->save();
				}
			}
		}
    }
}

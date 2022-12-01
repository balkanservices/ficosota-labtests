<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\QaJournalIngredient;
use App\RecipeIngredient;
use App\RecipeIngredientOption;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class QaJournal extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = ['recipe_id', 'batch_number', 'batch_date'];

    public function ingredients()
    {
        return $this->hasMany('App\QaJournalIngredient');
    }

	public function recipe()
    {
        return $this->belongsTo('App\Recipe');
    }

    public function samplesLists()
    {
        return $this->hasMany('App\SamplesList')->where('deleted', 0);
    }

    public static function getNamesArray($includeEmpty = true) {
        $namesArr = [];

        if($includeEmpty) {
            $namesArr = ['' => ''];
        }

        $qaJournals = self::all('id', 'batch_number', 'batch_date', 'recipe_id')
            ->where('deleted', '=', 0);

        foreach($qaJournals as $qaJournal) {
            $recipeName = '';
            if($qaJournal->recipe) {
                $recipeName = $qaJournal->recipe->getName();
            }

            $namesArr[$qaJournal->id] = $recipeName . ' / #' .
                $qaJournal->batch_number . ' / ' .
                $qaJournal->batch_date;
        }

        return $namesArr;
    }

    public function getName() {
        return $this->recipe->getName() . ' | #' . $this->batch_number. ' / ' . $this->batch_date;
    }

    public function getShortName() {
        return '#' . $this->batch_number. ' / ' . $this->batch_date;
    }

    public function initOptions() {
        foreach($this->recipe->ingredients as $recipeIngredient) {
            if(sizeof($recipeIngredient->options)) {
                $qaJournalIngredient = new QaJournalIngredient([
                    'qa_journal_id' => $this->id,
                    'ingredient_id' => $recipeIngredient->id,
                    'option_id' => $recipeIngredient->options[0]->id
                ]);

                $this->ingredients()->save($qaJournalIngredient);
            }
        }
    }

    public function getSelectedOptions() {
        $selectedOptions = [];
        foreach($this->ingredients as $ingredient) {
            $selectedOptions[$ingredient->ingredient_id] = $ingredient;
        }

        return $selectedOptions;
    }

    public function updateSelectedOptions($ingredients, $optionsBatchNumbers, $optionsFsBatchNumbers) {
        foreach($ingredients as $ingredientId => $optionId) {
            $ingredient = QaJournalIngredient::where('qa_journal_id', '=', $this->id)
                    ->where('ingredient_id', '=', $ingredientId)->first();
            if(!empty($ingredient)) {
                $ingredient->option_id = $optionId;
                $ingredient->option_batch_number = $optionsBatchNumbers[$ingredientId];
                $ingredient->option_fs_batch_number = $optionsFsBatchNumbers[$ingredientId];
                $ingredient->update();
            } else {
                if($optionId) {
                    $ingredient = new QaJournalIngredient();
                    $ingredient->qa_journal_id = $this->id;
                    $ingredient->ingredient_id = $ingredientId;
                    $ingredient->option_id = $optionId;
                    $ingredient->option_batch_number = $optionsBatchNumbers[$ingredientId];
                    $ingredient->option_fs_batch_number = $optionsFsBatchNumbers[$ingredientId];
                    $ingredient->save();
                }
            }
        }
    }

    public function getSapValueInGrams() {
        $sapIngredient = RecipeIngredient::where('recipe_id', '=', $this->recipe()->first()->id)
                ->where('name', '=', 'SAP')
                ->first();
        if(!$sapIngredient) {
            return false;
        }

        $qaJournalIngredient = $this->ingredients()
                ->where('ingredient_id', '=', $sapIngredient->id)
                ->first();
        if(!$qaJournalIngredient) {
            return false;
        }

        $option = RecipeIngredientOption::find($qaJournalIngredient->option_id);
        if(!$option) {
            return false;
        }

        $consumption = $option->consumption_per_piece;
        $consumption = (float) str_replace(",", ".", $consumption);

        switch($option->metric_unit) {
            case "kg":
                return $consumption * 1000;

            case "g":
            case "gr":
            case "gram":
            case "grams":
                return $consumption;
        }

        return false;
    }
}

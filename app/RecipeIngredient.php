<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Support\Facades\DB;

class RecipeIngredient extends Model implements AuditableContract {

    use Auditable;

    protected $fillable = ['recipe_id', 'name', 'is_visible', 'order'];

    /**
     * Get the options for the ingredient.
     */
    public function options()
    {
        return $this->hasMany('App\RecipeIngredientOption');
    }

    public function recipe()
    {
        return $this->belongsTo('App\Recipe');
    }

    public function slug()
    {
        $text = $this->name;

        $text = preg_replace('~[^\\pL\d]+~u', '_', $text);

        $text = trim($text, '_');

        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        $text = strtolower($text);

        $text = preg_replace('~[^_\w]+~', '', $text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }

    public function markAllOptionsAsNotMain() {
        DB::table('recipe_ingredient_options')
                ->where('recipe_ingredient_id', $this->id)
                ->update(['main_material' => 0]);
    }

    public function hasCutLength() {
        if(in_array($this->name, [
            'Landing zone',
            'Frontal wings',
            'Tape tabs',
        ])) {
            return true;
        }

        return false;
    }

    public function hasElasticsCountAndElongation() {
        if(in_array($this->name, [
            'Cuff round elastics',
            'Leg round elastics',
        ])) {
            return true;
        }

        return false;
    }

    public function getPreviousRevisionRecipeIngredient() {
        $previousRecipe = $this->recipe->getPreviousRevision();
        if (!$previousRecipe) {
            return false;
        }
        foreach ($previousRecipe->ingredients as $previousIngredient) {
            if ($previousIngredient->name === $this->name) {
                return $previousIngredient;
            }
        }
        return false;
    }
}

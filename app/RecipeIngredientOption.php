<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class RecipeIngredientOption extends Model implements AuditableContract
{
    const TYPE_MAIN = 'main';
    const TYPE_ALTERNATIVE = 'alternative';
    const TYPE_RESERVE = 'reserve';

    use Auditable;

    protected $fillable = ['recipe_ingredient_id', 'name', 'width', 'supplier',
        'metric_unit', 'consumption_per_piece', 'comment', 'main_material', 'type', 'priority'];

    public function recipe_ingredient()
    {
        return $this->belongsTo('App\RecipeIngredient');
    }

    /**
     * @param string $field
     * @param string $query
     * @return array
     */
    public static function autocompleteValuesForField($field, $query) {
        $options = DB::table('recipe_ingredient_options')
            ->select($field . ' AS label')
            ->where($field, 'LIKE', $query . '%')
            ->distinct()
            ->get()
            ->toArray();
        return $options;
    }

    public static function getTypes() {
        return [
            self::TYPE_MAIN => __('recipes.ingredient_options.type.' . self::TYPE_MAIN ),
            self::TYPE_ALTERNATIVE => __('recipes.ingredient_options.type.' . self::TYPE_ALTERNATIVE ),
            self::TYPE_RESERVE => __('recipes.ingredient_options.type.' . self::TYPE_RESERVE ),
        ];
    }

    public static function getComparableFields() {
        return ['name', 'width', 'supplier',
        'metric_unit', 'consumption_per_piece', 'comment', 'main_material', 'type', 'priority'];
    }
}

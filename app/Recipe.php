<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Recipe extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = ['name', 'comment', 'rd_specialist_id',
        'revision_number', 'revision_date', 'in_effect_from',
        'final_version',
//        'product_id',
//        'valid_from', 'valid_to'
        ];

    protected $ingredientNames = [
        'Top Sheet',
        'Leg Cuffs',
        'ADL /Acquisition Distribution Layer/',
        'Top Core Wrapping',
        'Bottom Core Wrapping',
        'Full Core Wrapping',
        'Fluff pulp',
        'SAP',
        'Textile Back Sheet',
        'Landing zone',
        'Frontal wings',
        'Back wings',
        'Tape tabs',
        'Cuff round elastics',
        'Leg round elastics',
        'Wetness indicator',
        'Construction hot melt adhesive',
        'Elastics hot melt adhesive',
        'Front and Back panels',
        'Leg elastics',
        'Cuff elastics',
        'Back panels fixations',
    ];

    protected $ingredientNamesFM = [
        'Top Sheet',
        'Leg Cuffs',
        'ADL /Acquisition Distribution Layer/',
        'Top Core Wrapping',
        'Bottom Core Wrapping',
        'Full Core Wrapping',
        'Fluff pulp',
        'SAP',
        'Textile Back Sheet',
        'Landing zone',
        'Frontal wings',
        'Back wings',
        'Tape tabs',
        'Cuff round elastics',
        'Leg round elastics',
        'Wetness indicator',

        'Construction hot melt adhesive',
        'Construction hot melt adhesive TS, ADL, BS, BS side, FW, LZ', // (ново)
        'Construction hot melt adhesive Top CW, Bottom CW central', // (ново)
        'Construction hot melt adhesive Top CW ends and sides', // (ново)
        'Elastics hot melt adhesive',
//        'Front and Back panels',
//        'Leg elastics',
//        'Cuff elastics',
        'Elastics hot melt adhesive LE', // (преименувано от Leg elastics)
        'Elastics hot melt adhesive CE', // (преименувано от Cuff elastics)
//        'Back panels fixations',
    ];


    protected $ingredientNamesGDM = [
        'Top Sheet',
        'Leg Cuffs',
        'ADL /Acquisition Distribution Layer/',
        'Top Core Wrapping',
        'Bottom Core Wrapping',
        'Full Core Wrapping',
        'Fluff pulp',
        'SAP',
        'Textile Back Sheet',
        'Landing zone',
        'Frontal wings',
        'Back wings',
        'Tape tabs',
        'Cuff round elastics',
        'Leg round elastics',
        'Wetness indicator',

        'Construction hot melt adhesive',
        'Construction hot melt adhesive Top CW, Bottom CW', // (ново)
        'Construction hot melt adhesive BS', // (ново)
        'Construction hot melt adhesive ADL, TS', // (ново)
        'Construction hot melt adhesive LZ', // (ново)
        'Construction hot melt adhesive Front and Back panels', // (преименувано от Front and Back panels)
        'Elastics hot melt adhesive',
//        'Front and Back panels',
//        'Leg elastics',
//        'Cuff elastics',
        'Elastics hot melt adhesive LE', // (преименувано от Leg elastics)
        'Elastics hot melt adhesive CE', // (преименувано от Cuff elastics)

        'Back panels fixations',
    ];


	/**
     * Get the ingredients for the recipe.
     */
    public function ingredients()
    {
        return $this->hasMany('App\RecipeIngredient');
    }

    public function visibleIngredients()
    {
        return $this->ingredients->where('is_visible', 1)->sortBy('order');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function usedMaterials()
    {
        return $this->hasMany('App\QaJournal')->where('deleted', 0);
    }

    public function initIngredients() {
        $this->reinitIngredients();
    }

    public function reinitIngredients() {
        $this->updateIngredientsNames();
        $this->addMissingIngredients();
        $this->refreshIngredientsOrderAndVisibility();
    }

    public static function getNamesArray($includeEmpty = true) {
        $namesArr = [];

        if($includeEmpty) {
            $namesArr = ['' => ''];
        }

        $recipes = self::where('deleted', 0)->orderBy('id', 'desc')->get();

        foreach($recipes as $recipe) {
            $namesArr[$recipe->id] = $recipe->getName();
        }

        return $namesArr;
    }

    public function rdSpecialist()
    {
        return $this->belongsTo('App\User', 'rd_specialist_id');
    }

    public static function getLatestRevisionNumberForProductId($productId) {
        $latestRevision = DB::table('recipes')
                ->where('product_id', '=', $productId)
                ->where('deleted', '=', 0)
                ->max('revision_number');
        if(!$latestRevision) {
            $latestRevision = 0;
        }
        return $latestRevision;
    }

    public static function getFirstRevisionNumberForProductId($productId) {
        $firstRevision = DB::table('recipes')
                ->where('product_id', '=', $productId)
                ->where('deleted', '=', 0)
                ->min('revision_number');
        if(!$firstRevision) {
            $firstRevision = 0;
        }
        return $firstRevision;
    }

    public function duplicate() {
        try {
            DB::beginTransaction();
            $newRecipe = $this->replicate();

            $newRecipe->revision_number = Recipe::getLatestRevisionNumberForProductId($this->product_id) + 1;
            $newRecipe->revision_date = date('Y-m-d');

            $newRecipe->save();

            $ingredients = $this->ingredients()->get();
            foreach($ingredients as $ingredient) {
                $newIngredient = $ingredient->replicate();
                $newIngredient->recipe_id = $newRecipe->id;
                $newIngredient->save();

                $options = $ingredient->options()->get();
                foreach($options as $option) {
                    $newOption = $option->replicate();
                    $newOption->recipe_ingredient_id = $newIngredient->id;
                    $newOption->save();

                    $newIngredient->options()->save($newOption);
                }

                $newRecipe->ingredients()->save($newIngredient);
            }

            $newRecipe->reinitIngredients();

            DB::commit();
            return $newRecipe;

        } catch( \Exception $e ) {
            DB::rollBack();
            return false;
        }
    }

    public function getName() {
        $product = $this->product()->first();
        $name = '';

        $name .= '#' . $this->revision_number;

        if(!empty($product)) {
            $name .= ' ' . $product->brand;
            $name .= ' ' . $product->conception;
            $name .= ' ' . $product->size;
        }

        if(!empty($this->revision_date)) {
            $name .= ' ' . $this->revision_date;
        }

        if(!empty($product)) {
            $name .= ' ' . $product->machine;
        }

        return trim($name);
    }

    public function getShortName() {
        $name = '';

        if(!empty($this->revision_number)) {
            $name .= ' #' . $this->revision_number;
        }
        if(!empty($this->revision_date)) {
            $name .= ' / ' . $this->revision_date;
        }

        return trim($name);
    }

    public static function getActiveRecipes($pageSize, $concept, $machine) {
        return self::join(\DB::raw('(SELECT product_id, MAX(revision_number) max_revision_number FROM recipes WHERE deleted = 0 GROUP BY product_id) AS r2'), function($join) {
                $join->on('recipes.product_id', '=', 'r2.product_id');
                $join->on('recipes.revision_number', '=', 'r2.max_revision_number');
            })
            ->join(\DB::raw('(SELECT id as product_id, brand, conception, machine FROM products) AS p'), function($join) {
                $join->on('recipes.product_id', '=', 'p.product_id');
            })
            ->where('deleted', 0)
            ->where(DB::raw("CONCAT(COALESCE(p.brand, ''), ' ', COALESCE(p.conception, ''))"), $concept)
            ->where('p.machine', $machine)
            ->orderBy('recipes.id', 'desc')
            ->paginate($pageSize);
    }

    public static function getNotActiveRecipes($pageSize, $concept, $machine, $size) {
        return self::leftJoin(\DB::raw('(SELECT product_id, MAX(revision_number) max_revision_number FROM recipes WHERE deleted = 0 GROUP BY product_id) AS r2'), function($join) {
                $join->on('recipes.product_id', '=', 'r2.product_id');
            })
            ->join(\DB::raw('(SELECT id as product_id, brand, conception, machine, size FROM products) AS p'), function($join) {
                $join->on('recipes.product_id', '=', 'p.product_id');
            })
            ->where('deleted', 0)
            ->where(function($query) {
                $query->whereColumn('recipes.revision_number', '!=', 'r2.max_revision_number');
                $query->orWhereNull('recipes.revision_number');
            })
            ->where(DB::raw("CONCAT(COALESCE(p.brand, ''), ' ', COALESCE(p.conception, ''))"), $concept)
            ->where('p.machine', $machine)
            ->where('p.size', $size)
            ->orderBy('recipes.id', 'desc')
            ->paginate($pageSize);
    }

    public function isLatestRevision() {
        return $this->revision_number === self::getLatestRevisionNumberForProductId($this->product_id) || $this->revision_number === null;
    }

    public function getPreviousRevision() {
        $prevRevision = self::where('product_id', '=', $this->product_id)
            ->where('revision_number', '=', $this->revision_number - 1)
            ->first();

        if ($prevRevision) {
            return $prevRevision;
        }

        return false;
    }

    public function getNextRevision() {
        if ($this->isLatestRevision()) {
            return false;
        }

        $nextRevision = self::where('product_id', '=', $this->product_id)
            ->where('revision_number', '=', $this->revision_number + 1)
            ->first();

        if ($nextRevision) {
            return $nextRevision;
        }

        return false;
    }

    public static function getLatestRevisionRecipeForProduct($productId) {
        $productLatestRecipeRevisionNumber = Recipe::getLatestRevisionNumberForProductId($productId);
        return Recipe::where('product_id', '=', $productId)
                ->where('revision_number', '=', $productLatestRecipeRevisionNumber)
                ->where('deleted', '=', 0)
                ->first();
    }

    public static function getFirstRevisionRecipeForProduct($productId) {
        $productFirstRecipeRevisionNumber = Recipe::getFirstRevisionNumberForProductId($productId);
        return Recipe::where('product_id', '=', $productId)
                ->where('revision_number', '=', $productFirstRecipeRevisionNumber)
                ->where('deleted', '=', 0)
                ->first();
    }

    public static function getRecipeForProductByRevisionNumber($productId, $revisionNumber) {
        return Recipe::where('product_id', '=', $productId)
                ->where('revision_number', '=', $revisionNumber)
                ->where('deleted', '=', 0)
                ->first();
    }

    private function getRecipeIngredientOrderValue($machine, $ingredient) {
        $ingredients = $this->getRecipeIngredientsByMachine($machine);

        foreach ($ingredients as $order => $ingredientName) {
            if (strcasecmp($ingredient, $ingredientName) === 0) {
                return $order;
            }
        }

        return false;
    }

    private function getRecipeIngredientVisibility($machine, $ingredient) {
        $ingredients = $this->getRecipeIngredientsByMachine($machine);

        foreach ($ingredients as $ingredientName) {
            if (strcasecmp($ingredient, $ingredientName) === 0) {
                return true;
            }
        }

        return false;
    }

    private function getRecipeIngredientsByMachine($machine) {
        switch ($machine) {
            case 'Fameccanica':
                return $this->ingredientNamesFM;
            case 'GDM':
                return $this->ingredientNamesGDM;
            default:
                throw new \Exception('Wrong machine.');
        }
    }

    private function isRecipeIngredientAvailable($recipeIngredientName, $recipeIngredients) {
        foreach ($recipeIngredients as $recipeIngredient) {
            if (strcasecmp($recipeIngredient->name, $recipeIngredientName) === 0) {
                return true;
            }
        }

        return false;
    }

    private function addMissingIngredients() {
        $recipeIngredients = $this->ingredients()->get();

        $machine = $this->product->machine;
        $machineIngredients = $this->getRecipeIngredientsByMachine($machine);

        foreach ($machineIngredients as $machineIngredientName) {
            if (!$this->isRecipeIngredientAvailable($machineIngredientName, $recipeIngredients)) {
                $ingredient = new RecipeIngredient([
                    'name' => $machineIngredientName
                ]);
                $this->ingredients()->save($ingredient);
            }
        }
    }

    private function refreshIngredientsOrderAndVisibility() {
        $recipeIngredients = $this->ingredients()->get();

        $machine = $this->product->machine;

        foreach ($recipeIngredients as $recipeIngredient) {
            $visibile = $this->getRecipeIngredientVisibility($machine, $recipeIngredient->name);

            if ($visibile) {
                $orderValue = $this->getRecipeIngredientOrderValue($machine, $recipeIngredient->name);

                $recipeIngredient->is_visible = true;
                $recipeIngredient->order = $orderValue;
            } else {
                $recipeIngredient->is_visible = false;
                $recipeIngredient->order = 0;
            }

            $recipeIngredient->save();
        }
    }

    private function updateIngredientsNames() {
        $recipeIngredients = $this->ingredients()->get();

        foreach ($recipeIngredients as $recipeIngredient) {

            if (strcasecmp($recipeIngredient->name, 'Leg elastics') === 0) {
                $recipeIngredient->name = 'Elastics hot melt adhesive LE';
            }

            if (strcasecmp($recipeIngredient->name, 'Cuff elastics') === 0) {
                $recipeIngredient->name = 'Elastics hot melt adhesive CE';
            }

            if (strcasecmp($recipeIngredient->name, 'Front and Back panels') === 0) {
                $recipeIngredient->name = 'Construction hot melt adhesive Front and Back panels';
            }

            $recipeIngredient->save();
        }
    }
}

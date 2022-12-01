<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Recipe;
use App\Product;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\RecipeRequest;
use App\RecipeIngredientOption;
use App\User;
use App\RecipeIngredient;
use Illuminate\Support\Facades\Lang;
use App\Helpers\RecipeExportHelper;

class RecipesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index($locale, $concept = null, $machine = null)
    {
        if (empty($concept)) {
            $conceptsArr = Product::getActiveRecipesProductConcepts();
            return view('recipes/index_products', [
                'conceptsArr' => $conceptsArr,
                'concept' => $concept,
                'machine' => $machine,
                'size' => null,
                'active' => true,
            ]);
        } elseif (!in_array($machine, Product::getMachines())) {
            return view('recipes/index_machines', [
                'machines' => Product::getActiveRecipesMachinesForConcept($concept),
                'concept' => $concept,
                'machine' => $machine,
                'size' => null,
                'active' => true,
            ]);
        }

        $recipes = Recipe::getActiveRecipes(self::PAGE_SIZE, $concept, $machine);

        return view('recipes/index', [
			'recipes' => $recipes,
            'concept' => $concept,
            'machine' => $machine,
            'size' => null,
            'active' => true,
		]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function indexNotActive($locale, $concept = null, $machine = null, $size = null)
    {
        if (empty($concept)) {
            $conceptsArr = Product::getNotActiveRecipesProductConcepts();
            return view('recipes/index_products', [
                'conceptsArr' => $conceptsArr,
                'concept' => $concept,
                'machine' => $machine,
                'size' => $size,
                'active' => false,
            ]);
        } elseif (!in_array($machine, Product::getMachines())) {
            return view('recipes/index_machines', [
                'machines' => Product::getNotActiveRecipesMachinesForConcept($concept),
                'concept' => $concept,
                'machine' => $machine,
                'size' => $size,
                'active' => false,
            ]);
        } elseif (empty($size)) {
            return view('recipes/index_sizes', [
                'sizes' => Product::getNotActiveRecipesSizesForConceptAndMachine($concept, $machine),
                'concept' => $concept,
                'machine' => $machine,
                'size' => $size,
                'active' => false,
            ]);
        }
        $recipes = Recipe::getNotActiveRecipes(self::PAGE_SIZE, $concept, $machine, $size);

        return view('recipes/index', [
			'recipes' => $recipes,
            'concept' => $concept,
            'machine' => $machine,
            'size' => $size,
            'active' => false,
		]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function edit($locale, $id)
    {
		$recipe = Recipe::find($id);

        if ($recipe->deleted) {
            return redirect()->route('recipes.index');
        }

        $products = Product::getNamesArray();

        $rdSpecialists = User::getRdSpecialistsArr();

        $optionTypes = RecipeIngredientOption::getTypes();

        //Disabled by Ficosota's request
//        $view = 'recipes/view';
//        if ($recipe->isLatestRevision()) {
            $view = 'recipes/edit';
//        }

        return view($view, [
			'recipe' => $recipe,
            'products' => $products,
            'rdSpecialists' => $rdSpecialists,
            'optionTypes' => $optionTypes,
		]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function view($locale, $id)
    {
		$recipe = Recipe::find($id);

        if ($recipe->deleted) {
            return redirect()->route('recipes.index');
        }

        $products = Product::getNamesArray();

        $rdSpecialists = User::getRdSpecialistsArr();

        $optionTypes = RecipeIngredientOption::getTypes();

        $view = 'recipes/view';

        return view($view, [
			'recipe' => $recipe,
            'products' => $products,
            'rdSpecialists' => $rdSpecialists,
            'optionTypes' => $optionTypes,
		]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function newRecord(Request $request, $locale)
    {
        $productId = (int) $request->query->get('productId');

        if(empty($productId)) {
            $products = Product::getPufiesNamesArray();

            return view('recipes/choose-product', [
                'products' => $products,
            ]);
        }

        $product = Product::findOrFail($productId);

        $recipe = Recipe::getLatestRevisionRecipeForProduct($product->id);

        if (empty($recipe)) {
            $recipe = new Recipe([
                'name' => __('recipes.name_new'),
            ]);

            $recipe->product_id = $product->id;
            $recipe->revision_number = 0;
            $recipe->revision_date = date('Y-m-d');

            $recipe->save();

            $recipe->initIngredients();
        }

        return redirect()->route('recipes.edit', ['id' => $recipe->id]);
    }

    public function process(RecipeRequest $request, $locale, $id)
    {
        $recipe = Recipe::findOrFail($id);

        $isRecipeFinal = $recipe->final_version;

        //Disabled by Ficosota's request
//        if (!$recipe->isLatestRevision()) {
//            $request->session()->flash('status', __('recipes.status.can_only_edit_latest_revision'));
//            return redirect()->route('recipes.edit', ['recipeId' => $recipe->id]);
//        }

        $requestArr = $request->all();
        if (empty($request->get('final_version'))) {
            $requestArr['final_version'] = 0;
        } else {
            $requestArr['final_version'] = 1;
        }

        if(empty($recipe)) {
            $recipe = new Recipe($requestArr);
            $recipe->save();
        } else {
            if ($isRecipeFinal) {
                $recipe->final_version_edited = true;
                $recipe->final_version_edited_at = date('Y-m-d H:i:s');
            }
            $recipe->update($requestArr);
        }

        if(!empty($request->get('save_and_create_qa_journal'))) {
            return redirect()->route('qa_journals.new', ['recipeId' => $recipe->id]);
        } elseif(!empty($request->get('save_and_create_new_revision'))) {
            $newRecipe = $recipe->duplicate();
            if(!$newRecipe) {
                $request->session()->flash('status', __('recipes.status.could_not_create_new_revision'));
                return redirect()->route('recipes.index');
            }
            return redirect()->route('recipes.edit', ['recipeId' => $newRecipe->id]);
        }

        $request->session()->flash('status', __('recipes.saved'));
        return redirect()->route('recipes.edit', ['id' => $recipe->id]);
    }

    public function delete(RecipeRequest $request, $locale, $id)
    {
        $recipe = Recipe::findOrFail($id);

        if (!$recipe->usedMaterials->isEmpty() || !$recipe->isLatestRevision()) {
            return redirect()->route('recipes.index');
        }

        $recipe->deleted = 1;
        $recipe->save();

        $request->session()->flash('status', __('recipes.deleted'));

        return redirect()->route('recipes.index');
    }

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function optionsJson(Request $request, $locale, $ingredientId) {
        $recipeIngredient = RecipeIngredient::find($ingredientId);
        $isFirstRevision = ($recipeIngredient->recipe->revision_number == 1);
        $previousRecipeIngredient = $recipeIngredient->getPreviousRevisionRecipeIngredient();
        if ($previousRecipeIngredient) {
            $previousRecipeIngredientOptions = $previousRecipeIngredient->options->sortBy('id');
        } else {
            $previousRecipeIngredientOptions = false;
        }

		$options = DB::table('recipe_ingredient_options')->where('recipe_ingredient_id', '=', $ingredientId)->get()->toArray();

        foreach($options as $index => $option) {
            $options[$index] = (array) $option;
            $options[$index]['main_material'] = ($option->main_material ? true : false);
            $options[$index]['has_changed'] = $isFirstRevision ? false : $this->hasOptionChanged((array)$option, $previousRecipeIngredientOptions);
        }

        return DataTables::of($options)->make();
	}

    private function hasOptionChanged($option, $previousOptions) {
        if (empty($previousOptions)) {
            return true;
        }
        $hasChanged = true;

        foreach ($previousOptions->toArray() as $previousOption) {
            $previousOptionArr = (array) $previousOption;

            $sameOption = true;
            foreach (RecipeIngredientOption::getComparableFields() as $field) {
                if ($previousOptionArr[$field] !== $option[$field]) {
                    $sameOption = false;
                }
            }

            if ($sameOption) {
                $hasChanged = false;
                break;
            }
        }

        return $hasChanged;
    }

    public function optionsPost(Request $request, $locale, $ingredientId) {

        $recipeIngredient = RecipeIngredient::find($ingredientId);
        $isFirstRevision = ($recipeIngredient->recipe->revision_number == 1);

        //Disabled by Ficosota's request
//        if (!$recipeIngredient || !$recipeIngredient->recipe->isLatestRevision()) {
//            return response()->json([
//                'error' => [
//                    Lang::trans('recipes.status.can_only_edit_latest_revision')
//                ]
//            ]);
//        }

        $previousRecipeIngredient = $recipeIngredient->getPreviousRevisionRecipeIngredient();
        if ($previousRecipeIngredient) {
            $previousRecipeIngredientOptions = $previousRecipeIngredient->options;
        } else {
            $previousRecipeIngredientOptions = false;
        }

		$action = $request->request->get('action');
		$dataArr = $request->request->get('data');
		foreach($dataArr as $optionId => $optionArr ) {
			switch($action) {
				case 'create':
					$ingredientOption = new RecipeIngredientOption($optionArr);
                    $ingredientOption->recipe_ingredient_id = $ingredientId;
					$ingredientOption->save();

                    $optionArr = $ingredientOption->attributesToArray();
                    $optionArr['has_changed'] = true;
					break;

				case 'edit':
					$ingredientOption = RecipeIngredientOption::find($optionId);
					foreach($optionArr as $key => $value) {
                        if($key == 'main_material') {
                            $ingredient = \App\RecipeIngredient::find($ingredientId);
                            $ingredient->markAllOptionsAsNotMain();
                        }
						$ingredientOption->$key = $value;
					}
					$ingredientOption->update();

                    $optionArr = $ingredientOption->attributesToArray();
                    $optionArr['has_changed'] = $isFirstRevision ? false : $this->hasOptionChanged($ingredientOption->toArray(), $previousRecipeIngredientOptions);
                    break;
			}
		}

        $recipe = $recipeIngredient->recipe;
        if ($recipe->final_version) {
            $recipe->final_version_edited = true;
            $recipe->final_version_edited_at = date('Y-m-d H:i:s');
            $recipe->update();
        }

		return response()->json([
			'data' => [
				$optionArr
			]
		]);
	}

    public function optionsAutocompleteJson(Request $request, $locale) {

        $field = '';
        $query = $request->query('q');

        switch($request->query('type')) {
            case 'trade_name':
                $field = 'name';
                break;
            case 'width':
            case 'supplier':
            case 'metric_unit':
            case 'consumption_per_piece':
                $field = $request->query('type');
                break;

            default:
                return response()->json([]);
        }

        $options = RecipeIngredientOption::autocompleteValuesForField($field, $query);

		return response()->json($options);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function audit($locale, $id)
    {
		$recipe = Recipe::findOrFail($id);
        $auditLog = $recipe->audits()->with('user')->orderBy("id", "DESC")->get();

        $recipeIngredients = $recipe->ingredients()->get();

        foreach($recipeIngredients as $recipeIngredient) {
            $ingredientOptions = $recipeIngredient->options()->get();
            foreach($ingredientOptions as $ingredientOption) {
                $auditLogTmp = $ingredientOption->audits()->with('user')->orderBy("id", "DESC")->get();
                $auditLog = $auditLog->merge($auditLogTmp);
            }
        }

        $auditLog = $auditLog->sortByDesc(function ($item, $key) {
            return $item->created_at;
        });

        return view('recipes/audit', [
			'recipe' => $recipe,
            'auditLog' => $auditLog,
		]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function auditAllRevisions($locale, $id)
    {
		$recipe = Recipe::findOrFail($id);
        $recipeFirstRevision = Recipe::getFirstRevisionRecipeForProduct($recipe->product->id);

        $auditLog = $recipeFirstRevision->audits()->with('user')->orderBy("id", "DESC")->get();

        $recipeIngredients = $recipeFirstRevision->ingredients()->get();

        foreach($recipeIngredients as $recipeIngredient) {
            $ingredientOptions = $recipeIngredient->options()->get();
            foreach($ingredientOptions as $ingredientOption) {
                $auditLogTmp = $ingredientOption->audits()->with('user')->orderBy("id", "DESC")->get();
                $auditLog = $auditLog->merge($auditLogTmp);
            }
        }

        $recipeNextRevision = $recipeFirstRevision->getNextRevision();
        while ($recipeNextRevision) {

                $auditLogRevision = $recipeNextRevision->audits()->with('user')->where('event', 'updated')->orderBy("id", "DESC")->get();
                $auditLog = $auditLog->merge($auditLogRevision);

                $recipeIngredients = $recipeNextRevision->ingredients()->get();

                foreach($recipeIngredients as $recipeIngredient) {
                    $ingredientOptions = $recipeIngredient->options()->get();
                    foreach($ingredientOptions as $ingredientOption) {
                        $auditLogTmp = $ingredientOption->audits()->with('user')->where('event', 'updated')->orderBy("id", "DESC")->get();
                        $auditLog = $auditLog->merge($auditLogTmp);
                    }
                }
                $recipeNextRevision = $recipeNextRevision->getNextRevision();
        }

        $auditLog = $auditLog->sortByDesc(function ($item, $key) {
            return $item->created_at;
        });

        return view('recipes/audit_all_revisions', [
			'recipe' => $recipe,
            'auditLog' => $auditLog,
		]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function downloadWordFile($locale, $id)
    {
		$recipe = Recipe::find($id);

        if ($recipe->deleted) {
            return redirect()->route('recipes.index');
        }

        $rdSpecialists = User::getRdSpecialistsArr();

        $optionTypes = RecipeIngredientOption::getTypes();

        RecipeExportHelper::createWordFile($locale, $recipe, $rdSpecialists, $optionTypes);

        die();
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function downloadPdfFile($locale, $id)
    {
		$recipe = Recipe::find($id);

        if ($recipe->deleted) {
            return redirect()->route('recipes.index');
        }

        $rdSpecialists = User::getRdSpecialistsArr();

        $optionTypes = RecipeIngredientOption::getTypes();

        RecipeExportHelper::createPdfFile($locale, $recipe, $rdSpecialists, $optionTypes);

        die();
    }
}

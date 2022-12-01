<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Recipe;
use App\RecipeIngredient;

class RecipesReinitIngredients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recipe:reinit-ingredients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reinits recipes ingredients';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $recipes = Recipe::whereHas('product', function ($query) {
            $query->where('machine', '=', 'Fameccanica')
                    ->orWhere('machine', '=', 'GDM');
        })->get();

        foreach ($recipes as $recipe) {
            $recipe->reinitIngredients();
        }
    }
}

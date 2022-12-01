<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\LanguageLineHelper;

class AddTranslation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        LanguageLineHelper::updateOrCreateLanguageLine("qa_journals", "choose_recipe", ["bg" => "Изберете рецепта, за която да се създаде ОТК журнал"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

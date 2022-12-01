<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\LanguageLineHelper;

class AverageValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        LanguageLineHelper::updateOrCreateLanguageLine("samples_list", "average_values", ["bg" => "Осреднени стойности"]);
        LanguageLineHelper::updateOrCreateLanguageLine("samples_list", "errors.no_analyses_done", ["bg" => "Няма направени анализи"]);
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

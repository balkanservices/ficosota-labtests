<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\LanguageLineHelper;

class NewProductTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        LanguageLineHelper::updateOrCreateLanguageLine("products", "brand", ["bg" => "Марка"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "conception", ["bg" => "Концепция"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "size", ["bg" => "Размер"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "kg_range", ["bg" => "Range, kg"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "region", ["bg" => "Регион"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "market", ["bg" => "Пазар"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "country_of_origin", ["bg" => "Страна на произход"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "producer", ["bg" => "Производител"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "date_of_production", ["bg" => "Дата на производство"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "batch", ["bg" => "Партида"]);
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

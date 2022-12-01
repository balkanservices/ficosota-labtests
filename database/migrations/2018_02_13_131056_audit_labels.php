<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\LanguageLineHelper;

class AuditLabels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        LanguageLineHelper::updateOrCreateLanguageLine("products", "btn.audit", ["bg" => "История на промените"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "back_to_product", ["bg" => "Обратно към продукта"]);

        LanguageLineHelper::updateOrCreateLanguageLine("recipes", "btn.audit", ["bg" => "История на промените"]);
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", "back_to_recipe", ["bg" => "Обратно към рецептурата"]);

        LanguageLineHelper::updateOrCreateLanguageLine("qa_journals", "btn.audit", ["bg" => "История на промените"]);
        LanguageLineHelper::updateOrCreateLanguageLine("qa_journals", "back_to_qa_journals", ["bg" => "Обратно към вложените материали"]);

        LanguageLineHelper::updateOrCreateLanguageLine("samples_list", "btn.audit", ["bg" => "История на промените"]);
        LanguageLineHelper::updateOrCreateLanguageLine("samples_list", "back_to_samples_list", ["bg" => "Обратно към дневника мостри"]);
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

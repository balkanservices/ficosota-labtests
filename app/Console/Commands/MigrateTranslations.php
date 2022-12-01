<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\LanguageLine;
use App\Helpers\LanguageLineHelper;

class MigrateTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:migrate_from_spatie';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates translations from "Laravel translation loader"';

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
        $languageLines = LanguageLine::all();

        foreach ($languageLines as $languageLine) {
            LanguageLineHelper::updateOrCreateLanguageLine(
                $languageLine->group,
                $languageLine->key,
                \json_decode($languageLine->text, true)
            );
        }
    }
}

<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ProductsSeeder::class);
		$this->call(RecipesSeeder::class);
		$this->call(QaJournalSeeder::class);
        $this->call(SamplesListsSeeder::class);
        $this->call(SamplesPackagesSeeder::class);
        $this->call(SamplesSeeder::class);
        $this->call(AnalysisDefinitionSeeder::class);
        $this->call(LanguageLinesSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(AnalysisDefinitionSeeder2::class);
    }
}

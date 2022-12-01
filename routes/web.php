<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@oldHome')->name('old_home');

Route::get('/products', 'ProductsController@index')->name('products.index');
Route::get('/product/new', 'ProductsController@newProduct')->name('products.new')->middleware(['auth', 'can:edit-products']);
Route::get('/product/audit/{id}', 'ProductsController@audit')->name('products.audit');
Route::get('/product/file_upload', 'ProductsController@fileUpload')->name('products.file_upload')->middleware(['auth', 'can:edit-products']);
Route::post('/product/file_upload', 'ProductsController@processFileUpload')->name('products.process_file_upload')->middleware(['auth', 'can:edit-products']);
Route::get('/product/{id}', 'ProductsController@edit')->name('products.edit');
Route::post('/product/{id}', 'ProductsController@process')->name('products.save')->middleware(['auth', 'can:edit-products']);
Route::get('/product/delete/{id}', 'ProductsController@delete')->name('products.delete')->middleware(['auth', 'can:edit-products']);

Route::get('/recipe/new', 'RecipesController@newRecord')->name('recipes.new')->middleware(['auth', 'can:edit-recipes']);
Route::get('/recipe/audit/{id}', 'RecipesController@audit')->name('recipes.audit');
Route::get('/recipe/audit-all-revisions/{id}', 'RecipesController@auditAllRevisions')->name('recipes.audit_all_revisions');
Route::get('/recipe/view/{id}', 'RecipesController@view')->name('recipes.view');
Route::get('/recipe/download-word/{id}', 'RecipesController@downloadWordFile')->name('recipes.download.word');
Route::get('/recipe/download-pdf/{id}', 'RecipesController@downloadPdfFile')->name('recipes.download.pdf');
Route::get('/recipe/{id}', 'RecipesController@edit')->name('recipes.edit');
Route::post('/recipe/{id}', 'RecipesController@process')->name('recipes.save')->middleware(['auth', 'can:edit-recipes']);

Route::get('/recipes/_options/{ingredientId}', 'RecipesController@optionsJson')->name('recipe.options_json');
Route::post('/recipes/_options/{ingredientId}', 'RecipesController@optionsPost')->name('recipe.options_json_post')->middleware(['auth', 'can:edit-recipes']);
Route::get('/recipes/_ingredients_autocomplete', 'RecipesController@optionsAutocompleteJson')->name('recipe.options_autocomplete_json');
Route::get('/recipes/delete/{id}', 'RecipesController@delete')->name('recipe.delete')->middleware(['auth', 'can:edit-recipes']);

Route::get('/recipes/not_active/{product_id?}/{machine?}/{size?}', 'RecipesController@indexNotActive')->name('recipes.index_not_active');
Route::get('/recipes/{concept?}/{machine?}', 'RecipesController@index')->name('recipes.index');

Route::get('/qa-journals', 'QaJournalsController@index')->name('qa_journals.index');
Route::get('/qa-journal/new', 'QaJournalsController@newRecord')->name('qa_journals.new')->middleware(['auth', 'can:edit-qa_journals']);
Route::get('/qa-journal/audit/{id}', 'QaJournalsController@audit')->name('qa_journals.audit');
Route::get('/qa-journal/{id}', 'QaJournalsController@edit')->name('qa_journals.edit');
Route::post('/qa-journal/{id}', 'QaJournalsController@process')->name('qa_journals.save')->middleware(['auth', 'can:edit-qa_journals']);
Route::get('/qa-journal/delete/{id}', 'QaJournalsController@delete')->name('qa_journals.delete')->middleware(['auth', 'can:edit-qa_journals']);

Route::get('/samples-lists', 'SamplesListController@index')->name('samples_list.index');
Route::get('/samples-list/_samples/{samplesPackageId}', 'SamplesListController@samplesJson')->name('samples_list.samples_json');
Route::post('/samples-list/_samples/{samplesPackageId}', 'SamplesListController@samplesPost')->name('samples_list.samples_json_post');
Route::post('/samples-list/_samples_add/{samplesPackageId}', 'SamplesListController@samplesAdd')->name('samples_list.samples.add');

Route::get('/samples-list/_packages/{samplesListId}', 'SamplesListController@packagesJson')->name('samples_list.packages.json');
Route::post('/samples-list/_packages/{samplesListId}', 'SamplesListController@packagesPost')->name('samples_list.packages.json_post')->middleware(['auth', 'can:edit-samples_lists']);
Route::post('/samples-list/_packages_rows_create/{samplesListId}', 'SamplesListController@createPackagesRow')->name('samples_list.packages.create_rows')->middleware(['auth', 'can:edit-samples_lists']);

Route::get('/samples-list/_package/{samplesPackageId}', 'SamplesListController@packageJson')->name('samples_list.package.json');
Route::post('/samples-list/_package/{samplesPackageId}', 'SamplesListController@packagePost')->name('samples_list.package.json_post')->middleware(['auth', 'can:edit-samples_lists']);
Route::get('/samples-list/package/{packageId}', 'SamplesListController@package')->name('samples_list.package.index');
Route::get('/samples-list/_package_assignments/{samplesPackageId}', 'SamplesListController@assignmentsJson')->name('samples_list.assignments.json');
Route::post('/samples-list/_package_assignments/{samplesPackageId}', 'SamplesListController@assignmentsPost')->name('samples_list.assignments.json_post')->middleware(['auth', 'can:edit-samples_lists']);
Route::get('/samples-list/_enabled_analyses/{samplesPackageId}', 'SamplesListController@enabledAnalysesJson')->name('samples_list.enabled_analyses.json');
Route::post('/samples-list/_enabled_analyses/{samplesPackageId}', 'SamplesListController@enabledAnalysesPost')->name('samples_list.enabled_analyses.json_post')->middleware(['auth', 'can:edit-samples_lists']);

Route::get('/samples-list/new', 'SamplesListController@newRecord')->name('samples_lists.new')->middleware(['auth', 'can:edit-samples_lists']);
Route::get('/samples-list/audit/{id}', 'SamplesListController@audit')->name('samples_lists.audit');
Route::get('/samples-list/audit_package/{id}', 'SamplesListController@auditPackage')->name('samples_lists.audit_package');
Route::get('/samples-list/audit_analysis/{samplesPackageId}/{analysis}', 'SamplesListController@auditAnalysis')->name('samples_lists.audit_analysis');
Route::get('/samples-list/{id}', 'SamplesListController@edit')->name('samples_lists.edit');
Route::post('/samples-list/{id}', 'SamplesListController@process')->name('samples_lists.save')->middleware(['auth', 'can:edit-samples_lists']);

Route::get('/samples-list/analysis/{samplesPackageId}/{analysis}/{samplesIds}', 'SamplesListController@doAnalysis')->name('samples_list.do_analysis');
Route::get('/samples-list/average_values/{samplesPackageId}/{analysis}', 'SamplesListController@averageValues')->name('samples_list.average_values');

Route::get('/samples-list/_analyses/{samplesPackageId}/{analysis}/{table}/{samplesIds}', 'SamplesListController@analysesJson')->name('samples_list.analyses_json');
Route::post('/samples-list/_analyses/{analysis}/{table}/{samplesIds}', 'SamplesListController@analysesPost')->name('samples_list.analyses_json_post')->middleware(['auth', 'can:edit-samples_lists']);
Route::get('/samples-list/_analyses/{samplesPackageId}/samples_count', 'SamplesListController@analysisSamplesCount')->name('samples_list.get_analysis_samples_count');

Route::get('/samples-list/_diaper_weights/{samplesPackageId}', 'SamplesListController@diaperWeightsJson')->name('samples_list.diaper_weights.json');
Route::get('/samples-list/delete/{id}', 'SamplesListController@delete')->name('samples_list.delete')->middleware(['auth', 'can:edit-samples_lists']);
Route::post('/samples-list/_analyses/{samplesPackageId}/samples_delete', 'SamplesListController@analysisSamplesDelete')->name('samples_list.analysis_samples_delete');

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'can:access-backend']], function(){
	Route::get('/users', 'UsersController@index')->name('users.index');
    Route::get('/users/audit/{id}', 'UsersController@audit')->name('users.audit');
    Route::get('/users/{id}', 'UsersController@edit')->name('users.edit');
    Route::post('/users/{id}', 'UsersController@process')->name('users.save');
});

Route::get('/performance-limits', 'PerformanceLimitsController@index')->name('performance_limits.index');
Route::get('/performance-limits/edit/{id}', 'PerformanceLimitsController@edit')->name('performance_limits.edit');
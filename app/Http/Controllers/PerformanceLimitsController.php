<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Sample;
use App\Analysis;
use App\Helpers\FieldsHelper;
use App\Helpers\AnalysesHelper;
use Illuminate\Support\Facades\DB;
use App\AnalysisDefinition;
use App\Helpers\TabIndexHelper;
use App\QaJournal;
use App\SamplesList;
use App\Http\Requests\SamplesListRequest;
use App\SamplesPackage;
use App\SamplesPackageDiaperWeights;
use App\Helpers\GroupedHeaderHelper;
use App\AnalysisLimits;
use App\Product;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class PerformanceLimitsController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $locale)
    {
		$searchText = $request->request->get('q');

        $columns = [
            [
                'table' => 'samples_list',
                'paramName' => 'sample_number',
                'label' => 'samples_list.sample_number',
                'values' => SamplesList::getDistinctValues('sample_number')
            ],
            [
                'table' => 'samples_list',
                'paramName' => 'rd_delivery_date',
                'label' => 'samples_list.rd_delivery_date',
                'values' => SamplesList::getDistinctValues('rd_delivery_date')
            ],
            [
                'table' => 'samples_list',
                'paramName' => 'priority',
                'label' => 'samples_list.priority',
                'values' => SamplesList::getDistinctValues('priority')
            ],
            [
                'table' => 'products',
                'paramName' => 'brand',
                'label' => 'products.brand',
                'values' => SamplesList::getConnectedProductsDistinctValues('brand')
            ],
            [
                'table' => 'products',
                'paramName' => 'conception',
                'label' => 'products.conception',
                'values' => SamplesList::getConnectedProductsDistinctValues('conception')
            ],
            [
                'table' => 'products',
                'paramName' => 'size',
                'label' => 'products.size',
                'values' => SamplesList::getConnectedProductsDistinctValues('size')
            ],
            [
                'table' => 'products',
                'paramName' => 'type',
                'label' => 'products.type',
                'values' => SamplesList::getConnectedProductsDistinctValues('type')
            ],
            [
                'table' => 'samples_list',
                'paramName' => 'market',
                'label' => 'samples_list.market',
                'values' => SamplesList::getDistinctValues('market')
            ],
            [
                'table' => 'samples_list',
                'paramName' => 'manifacturing_date',
                'label' => 'samples_list.manifacturing_date',
                'values' => SamplesList::getDistinctValues('manifacturing_date')
            ],
            [
                'table' => 'samples_list',
                'paramName' => 'batch',
                'label' => 'samples_list.batch',
                'values' => SamplesList::getDistinctValues('batch')
            ],
            [
                'table' => 'samples_list',
                'paramName' => 'manifacturer',
                'label' => 'samples_list.manifacturer',
                'values' => SamplesList::getDistinctValues('manifacturer')
            ],
        ];

        $filterParams = SamplesList::getFilterParams($request->all(), $columns);
        $samplesLists = SamplesList::listOrSearch($searchText, self::PAGE_SIZE, $filterParams);

        return view('performance-limits/index', [
            'samplesLists' => $samplesLists,
            'searchText' => $searchText,
            'brands' => Product::getDistinctValues('brand'),
            'columns' => $columns,
        ]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function edit($locale, $id)
    {
//        $samplesList = SamplesList::findOrFail($id);
//
//        if ($samplesList->deleted) {
//            return redirect()->route('samples_list.index');
//        }
//
//        $samplesPackage = $samplesList->packages()->first();
//
//        if (!$samplesPackage) {
//            $samplesPackage = new SamplesPackage();
//            $samplesPackage->samples_list_id = $samplesList->id;
//            $samplesPackage->comment = '';
//            $samplesPackage->samples_count = 0;
//            $samplesPackage->manifacturing_time = '00:00';
//            $samplesPackage->save();
//        }
//
//        $sampleListProduct = $samplesList->product()->first();
            $samplesList = new SamplesList();

            $fields = [
                'предни уши / Дължина на уши, mm (left) / Ширина и дължина / Уши',
                'предни уши / Дължина на уши, mm (right) / Ширина и дължина / Уши',
                'предни уши / Ширина на уши, mm (left) / Ширина и дължина / Уши',
                'предни уши / Ширина на уши, mm (right) / Ширина и дължина / Уши',
            ];
            $fields2 = [
                'при стойност за 1h: 1-3%',
                'при стойност за 1h: 4-10%',
                'при стойност за 1h: 11-40%',
            ];
        return view('performance-limits/edit', [
//			'fields'	=> FieldsHelper::sampleListFields(),
//			'analyses'	=> AnalysisDefinition::all(['slug']),
//            'analysisDefinitions'	=> AnalysisDefinition::all(),
            'qaJournals' => QaJournal::getNamesArray(),
            'products' => Product::getCompetitiveProductsNamesArray(),
            'samplesList' => $samplesList,
            'sampleListProduct' => null,//$sampleListProduct,
            'fields' => $fields,
            'fields2' => $fields2,
		]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function package($locale, $id)
    {
        $samplesPackage = SamplesPackage::findOrFail($id);
        $samplesList = $samplesPackage->samples_list()->first();

        $diaperWeightsLimitsJson = $samplesList->getAnalysisLimitsDiapersWeights();

        return view('samples-list/package', [
			'fields'	=> FieldsHelper::sampleListFields(),
			'analyses'	=> AnalysisDefinition::all(['slug']),
            'analysisDefinitions'	=> AnalysisDefinition::all(),
            'samplesList' => $samplesList,
            'qaJournals' => QaJournal::getNamesArray(),
            'samplesPackage' => $samplesPackage,
            'diaperWeightsLimitsJson' => $diaperWeightsLimitsJson,
		]);
    }

    /**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function packageJson($locale, $samplesPackageId) {
        $samplesPackage = SamplesPackage::findOrFail($samplesPackageId);

        return DataTables::of([$samplesPackage->toArray()])->make();
	}

	public function packagePost(Request $request, $locale, $samplesListId) {
		$action = $request->request->get('action');
		$dataArr = $request->request->get('data');
		foreach($dataArr as $samplesPackageId => $samplesPackageArr ) {
			switch($action) {
				case 'create':
					$samplesPackage = new SamplesPackage($samplesPackageArr);
                    $samplesPackage->samples_list_id = $samplesListId;
					$samplesPackage->save();
					break;

				case 'edit':
					$samplesPackage =  SamplesPackage::find($samplesPackageId);
					foreach($samplesPackageArr as $key => $value) {
						$samplesPackage->$key = $value;
					}
					$samplesPackage->update();
			}
		}

		$samplesPackageArr = $samplesPackage->attributesToArray();

		return response()->json([
			'data' => [
				$samplesPackageArr
			]
		]);
	}

    /**
     * @return \Illuminate\Http\Response
     */
    public function newRecord(Request $request, $locale)
    {
        $samplesList = new SamplesList([
            'name' => __('samples_list.name_new'),
        ]);

        $qaJournalId = $request->query->get('qaJournalId');
        if(!empty($qaJournalId) && is_numeric($qaJournalId)) {
            $samplesList->qa_journal_id = $qaJournalId;
        }

        $samplesList->save();
        $samplesList->sample_number = $samplesList->id;
        $samplesList->save();

        $samplesPackage = new SamplesPackage();
        $samplesPackage->samples_list_id = $samplesList->id;
        $samplesPackage->comment = '';
        $samplesPackage->samples_count = 0;
        $samplesPackage->manifacturing_time = '00:00';
        $samplesPackage->save();

        return redirect()->route('samples_lists.edit', ['id' => $samplesList->id]);
    }


	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function samplesJson($locale, $samplesPackageId) {
		$samplesArr = [];

        $samplesPackage = SamplesPackage::findOrFail($samplesPackageId);

        $samplesSummary = $samplesPackage->getSamplesSummary();

        foreach ($samplesSummary as $sample) {
            $sampleArr = [
                'id' => $sample->id,
                'weight' => $sample->weight,
                'analyses' => $sample->analyses ? : '',
                'assigned_analyses_completed' => $sample->assigned_analyses_completed,
            ];

            $samplesArr[] = $sampleArr;
        }

        return DataTables::of($samplesArr)->make();
	}

	public function samplesPost(Request $request, $locale, $samplesPackageId) {
		$action = $request->request->get('action');
		$dataArr = $request->request->get('data');
		foreach($dataArr as $sampleId => $sampleArr ) {
			switch($action) {
				case 'create':
					$sample = new Sample($sampleArr);
					$sample->save();
					break;

				case 'edit':
					$sample = Sample::findOrFail($sampleId);
					foreach($sampleArr as $key => $value) {
                        if($key == 'weight') {
                            $value = str_replace(',', '.', $value);
                            if (!is_numeric($value)) {
                                return response()->json([
                                    'error' => Lang::trans('samples_list.error.value_should_be_numeric')
                                ]);
                            }
                            $sample->$key = round((float) $value,2);
                        } elseif ($key == 'analyses') {
                            $sample->updateAnalyses($value);
                        }
					}
					$sample->update();
			}
		}

		$sampleArr = $sample->attributesToArray();
        if(!isset($sampleArr['analyses'])) {
            $sampleArr['analyses'] = '';
        }
		$sampleArr['analyses'] = $sample->getAnalysesDefinitionSlugs();
        $sampleArr['assigned_analyses_completed'] = $sample->getAssignedAnalysesCompletedString();

        $samplesPackage = SamplesPackage::find($sample->samples_package_id);
        $sampleArr['package_manifacturing_time'] = $samplesPackage->manifacturing_time;

		return response()->json([
			'data' => [
				$sampleArr
			]
		]);
	}

	public function samplesAdd(Request $request, $locale, $samplesPackageId) {
        $weight = $request->request->get('weight');

        $weight = str_replace(',', '.', $weight);
        if (!is_numeric($weight)) {
            return response()->json([
                'success' => false,
                'error' => Lang::trans('samples_list.error.value_should_be_numeric')
            ]);
        }

        $sample = new Sample();
        $sample->wip = 1;
        $sample->samples_package_id = $samplesPackageId;
        $sample->weight = round((float) $weight, 2);
        $sample->save();

		return response()->json([
			'success' => true
		]);
	}

    private function assignSamplesToAnalysis($samplesIdsArr, $samplesPackage, $analysisDefinition) {

        foreach ($samplesIdsArr as $sampleId) {
            $sample = Sample::findOrFail($sampleId);

            if ($sample->package()->first()->id !== $samplesPackage->id) {
                throw new \Exception('The sample is not a part of this package!');
            }
        }

        $sampleAnalyses = $this->_getSampleAnalyses($analysisDefinition->slug, $samplesPackage->getSamplesIdsArr());
        $idsToInsert = $samplesIdsArr;
        $sampleAnalysesArr = [];

		foreach ($sampleAnalyses as $sampleAnalysis) {
            $sampleAnalysesArr[] = $sampleAnalysis;
			if (($key = array_search($sampleAnalysis->sample_id, $idsToInsert)) !== false) {
				unset($idsToInsert[$key]);
			}
		}

        if($analysisDefinition->slug != 'color_stability' || !isset($sampleAnalyses[0])) {
            foreach($idsToInsert as $id) {
                $sampleAnalysis = new Analysis();
                $sampleAnalysis->sample_id = $id;
                $sampleAnalysis->definition()->associate($analysisDefinition);
                $sampleAnalysis->wip = 1;
                $sampleAnalysis->save();

                $sampleAnalysesArr[] = $sampleAnalysis;
            }
        } else {
            foreach($idsToInsert as $id) {
                $sampleAnalysis = $sampleAnalyses[0]->duplicate();
                $sampleAnalysis->sample_id = $id;
                $sampleAnalysis->save();
                $sampleAnalysesArr[] = $sampleAnalysis;
            }
        }

        return $sampleAnalysesArr;
    }

    private function sortSampleAnalysesArr(&$sampleAnalysesArr) {
        usort($sampleAnalysesArr, function ($a, $b){
            if ($a->sample->weight == $b->sample->weight) {
                return 0;
            }
            return $a->sample->weight < $b->sample->weight ? -1 : 1;
        });
    }

    private function sortAblSampleAnalysesArr(&$sampleAnalysesArr) {
        usort($sampleAnalysesArr, function ($a, $b){

            $attributeCmp = strcmp(
                $a->getAttributeValueOrNull('table_1__mannequin_position'),
                $b->getAttributeValueOrNull('table_1__mannequin_position')
            );



            if ($attributeCmp !== 0) {
                return $attributeCmp;
            }

            if ($a->sample->weight == $b->sample->weight) {
                return 0;
            }
            return $a->sample->weight < $b->sample->weight ? -1 : 1;
        });
    }

	public function doAnalysis(Request $request, $locale, $samplesPackageId, $analysis, $samplesIds)
    {
        $samplesPackage = SamplesPackage::findOrFail($samplesPackageId);

        if($samplesIds == 'all') {
            $samplesIdsArr = [];
            if($analysis == 'color_stability') {
                $samplesIdsArr = $samplesPackage->getAnalysisSamplesIdsArr('color_stability');
            }
        } else {
            $samplesIdsArr = explode(',', $samplesIds);
        }

        $analysisDefinition = AnalysisDefinition::where('slug', $analysis)->first();

        $sampleAnalysesArr = $this->assignSamplesToAnalysis($samplesIdsArr, $samplesPackage, $analysisDefinition);

        if ($analysisDefinition->slug != 'absorbtion_before_leakage') {
            $this->sortSampleAnalysesArr($sampleAnalysesArr);
        } else {
            $this->sortAblSampleAnalysesArr($sampleAnalysesArr);
        }

        foreach($sampleAnalysesArr as $sampleAnalysis) {
            if($analysis == 'superabsorbent_quantity') {
                $sample = $sampleAnalysis->sample()->first();
                $sampleAnalysis->setAttributeValue("sap_quantity__weight", $sample->weight);
                $qaJournal = $sample->getSamplesList()->qa_journal()->first();
                $sapValue = 0;
                if($qaJournal) {
                    $sapValue = (float) $sample->getSamplesList()->qa_journal()->first()->getSapValueInGrams();
                }
                $sampleAnalysis->setAttributeValue("sap_quantity__recipe_sap_quantity", $sapValue);
            }

            if($analysis == 'superabsorbent_quantity_zones') {
                $sample = $sampleAnalysis->sample()->first();
                $sampleAnalysis->setAttributeValue("weights__weight", $sample->weight);
            }

            if($analysis == 'total_absorption_capacity') {
                $sample = $sampleAnalysis->sample()->first();
                $sampleAnalysis->setAttributeValue("data__weight", $sample->weight);
            }

            if($analysis == 'absorption_against_pressure') {
                $sample = $sampleAnalysis->sample()->first();
                if (!$sampleAnalysis->getAttributeValueOrNull('data_1__weight_g_cm2')) {
                    $sampleAnalysis->setAttributeValue("data_1__weight_g_cm2", 50);
                }
                if (!$sampleAnalysis->getAttributeValueOrNull('data_2__weight_g_cm2')) {
                    $sampleAnalysis->setAttributeValue("data_2__weight_g_cm2", 50);
                }
            }

            if($analysis == 'absorbtion_before_leakage') {
                $sample = $sampleAnalysis->sample()->first();
                if (!$sampleAnalysis->getAttributeValueOrNull('table_1__flow_rate')) {
                    $sampleAnalysis->setAttributeValue("table_1__flow_rate", 4);
                }
                $sampleAnalysis->setAttributeValue("m_fields__m_dry_diaper", $sample->weight);
            }
        }

        $this->updateColorStabilitySamplesCount($analysisDefinition, $sampleAnalysesArr);

        $tabIndexHelper = new TabIndexHelper();
        $groupedHeaderHelper = new GroupedHeaderHelper();

        $analysisLimitsJson = $samplesPackage->samples_list->getAnalysisLimits($analysis);

        $analysesAttributesBySampleId = [];

        foreach ($sampleAnalysesArr as $sampleAnalysis) {
            $analysesAttributesBySampleId[$sampleAnalysis->sample_id] = $sampleAnalysis->getAttributesArray();
        }

        $analysesAttributesBySampleId['average_values'] = $this->calculateAndReturnAverageValuesArr($analysis, $samplesPackageId);

        if ($analysis == 'absorbtion_before_leakage') {
            $analysesAttributesBySampleId['average_values_belly_back'] = $this->calculateAndReturnAverageValuesArr(
                $analysis,
                $samplesPackageId,
                null,
                'table_1__mannequin_position',
                'belly/back'
            );
            $analysesAttributesBySampleId['average_values_sideways'] = $this->calculateAndReturnAverageValuesArr(
                $analysis,
                $samplesPackageId,
                null,
                'table_1__mannequin_position',
                'sideways'
            );
            $analysesAttributesBySampleId['average_values_standing'] = $this->calculateAndReturnAverageValuesArr(
                $analysis,
                $samplesPackageId,
                null,
                'table_1__mannequin_position',
                'standing'
            );
        }

        return view('samples-list/do_analysis', [
			'analysis'	=> $analysis,
            'samplesIds'	=> $samplesIds,
            'samplesIdsArr' => $samplesIdsArr,
            'analysisDefinition' => $analysisDefinition,
            'tabIndexHelper' => $tabIndexHelper,
            'sampleAnalyses' => $sampleAnalysesArr,
            'samplesPackage' => $samplesPackage,
            'groupedHeaderHelper' => $groupedHeaderHelper,
            'analysisLimitsJson' => $analysisLimitsJson,
            'analysesAttributesBySampleId' => $analysesAttributesBySampleId,
		]);
    }

    private function _getSampleAnalyses($analysis, $samplesIdsArr) {
        $definition = AnalysisDefinition::where('slug', $analysis)->first();

        if(count($samplesIdsArr) == 1 && $this->isColorStabilityId($samplesIdsArr[0])) {
            $samplesIds = $this->extractColorStabilityIds($samplesIdsArr[0]);

            $analysis = Analysis::whereIn('sample_id', $samplesIds)
                ->where('enabled', true)
                ->where('analysis_definition_id', $definition->id)
                ->first();
            return $analysis ? [$analysis] : [];
        }

		return Analysis::whereIn('sample_id', $samplesIdsArr)
                ->where('enabled', true)
                ->where('analysis_definition_id', $definition->id)
                ->get();
    }

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function analysesJson(Request $request, $locale, $samplesPackageId, $analysis, $table, $samplesIds) {
        $samplesIdsArr = explode(',', $samplesIds);

        switch ($samplesIds) {
            case 'average_values':
                return $this->calculateAndReturnAverageValues($analysis, $samplesPackageId, $table);

            case 'average_values_belly_back':
                return $this->calculateAndReturnAverageValues(
                    $analysis, $samplesPackageId, $table,
                    'table_1__mannequin_position',
                    'belly/back'
                );

            case 'average_values_sideways':
                return $this->calculateAndReturnAverageValues(
                    $analysis, $samplesPackageId, $table,
                    'table_1__mannequin_position',
                    'sideways'
                );

            case 'average_values_standing':
                return $this->calculateAndReturnAverageValues(
                    $analysis, $samplesPackageId, $table,
                    'table_1__mannequin_position',
                    'standing'
                );
        }


		$analyses = $this->_getSampleAnalyses($analysis, $samplesIdsArr);
        $analysesArr = [];

		foreach($analyses as $analysis) {
            $analysesArr[$analysis->id] = $analysis->getAttributesArray($table);
		}

        return DataTables::of($analysesArr)->make();
	}

	public function analysesPost(Request $request, $locale, $analysis, $table, $samplesIds) {
		$action = $request->request->get('action');
		$dataArr = $request->request->get('data');

        try {
            foreach($dataArr as $analysisId => $attributeValues ) {
                switch($action) {
    //				case 'create':
    //					$analysis = new Analysis($analysisArr);
    //					$analysis->save();
    //					break;

                    case 'edit':
                        $analysisCompletion = false;

                        if ($table === 'completion') {
                            if (!empty($attributeValues['completion__analysis_complete'])
                                && $attributeValues['completion__analysis_complete'][0] == 'completed') {
                                $attributeValues['completion__analysis_complete'] = 'completed';
                                $analysisCompletion = true;
                            } else {
                                $attributeValues['completion__analysis_complete'] = 'not_completed';
                            }
                        }

                        if($this->isColorStabilityId($samplesIds)) {
                            $analysisDefinition = AnalysisDefinition::where('slug', $analysis)->first();

                            $samplesIds = $this->extractColorStabilityIds($samplesIds);

                            foreach($samplesIds as $sampleId) {
                                $analysis = Analysis::where('analysis_definition_id', '=', $analysisDefinition->id)
                                        ->where('enabled', '=', true)
                                        ->where('sample_id', '=', $sampleId)->first();

                                $parentsToRefresh = $analysis->setMultipleAttributeValues($attributeValues);
                            }
                        } else {
                            $analysis = Analysis::find($analysisId);

                            $parentsToRefresh = $analysis->setMultipleAttributeValues($attributeValues);
                        }

                        if ($analysisCompletion) {
                            $samplesList = $analysis->sample->package->samples_list;

                            if ($samplesList->areAllPackageAnalysesCompleted()) {
                                if (empty($samplesList->analysis_end_date)) {
                                    $samplesList->analysis_end_date = date('Y-m-d');
                                    $samplesList->update();
                                }
                            }
                        }
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }

		$analysisArr = $analysis->getAttributesArray($table);

		return response()->json([
			'data' => [
				$analysisArr
			],
            'tables_to_refresh' => $parentsToRefresh,
		]);
	}

    private function isColorStabilityId($colorStabilityId) {
        if(strpos($colorStabilityId, 'color_stability__') === 0) {
            return true;
        }
        return false;
    }

    private function extractColorStabilityIds($colorStabilityId) {
        $colorStabilityArr = explode("__", $colorStabilityId);
        $samplesIds = explode('_', $colorStabilityArr[1]);

        $samplesPackage = Sample::findSamplesPackageBySampleId($samplesIds[0]);

        return $samplesPackage->getColorStabilitySamplesIdsArr();
    }

    private function updateColorStabilitySamplesCount($analysisDefinition, $sampleAnalysesArr) {
        if($analysisDefinition->slug != 'color_stability') {
            return;
        }

        $samplesCount = count($sampleAnalysesArr);

        foreach($sampleAnalysesArr as $analysis) {
            $analysis->setMultipleAttributeValues(['table_2__analyzed_diapers_count' => $samplesCount]);
        }

    }

    /**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function packagesJson($locale, $samplesListId) {
        $samplesPackages = SamplesPackage::where('samples_list_id', '=', $samplesListId)->get();
		$samplesPackagesArr = [];

		foreach($samplesPackages as $key => $samplePackage) {
			$samplePackageArr = $samplePackage->toArray();
			$samplesPackagesArr[$key] = $samplePackageArr;
		}

        return DataTables::of($samplesPackagesArr)->make();
	}

	public function packagesPost(Request $request, $locale, $samplesListId) {
        $samplesList = SamplesList::findOrFail($samplesListId);

		$action = $request->request->get('action');
		$dataArr = $request->request->get('data');
		foreach($dataArr as $samplesPackageId => $samplesPackageArr ) {
			switch($action) {
				case 'create':
					$samplesPackage = new SamplesPackage($samplesPackageArr);
                    $samplesPackage->samples_list_id = $samplesListId;
					$samplesPackage->save();
					break;

				case 'edit':
					$samplesPackage =  SamplesPackage::find($samplesPackageId);
					foreach($samplesPackageArr as $key => $value) {
						$samplesPackage->$key = $value;
					}
					$samplesPackage->update();
			}
		}


        if (empty($samplesList->analysis_start_date)) {
            $samplesList->analysis_start_date = date('Y-m-d');
            $samplesList->update();
        }

		$samplesPackageArr = $samplesPackage->attributesToArray();

		return response()->json([
			'data' => [
				$samplesPackageArr
			]
		]);
	}

    public function createPackagesRow(Request $request, $locale, $samplesListId) {
		$rows = (int) $request->request->get('new_rows');

		for($i=0; $i<$rows; $i++) {
			$samplesPackage = new SamplesPackage();
            $samplesPackage->samples_list_id = $samplesListId;
            $samplesPackage->manifacturing_time = '00:00';
            $samplesPackage->samples_count = 0;
            $samplesPackage->comment = '';
			$samplesPackage->save();
		}

		return response()->json([
			'success' => true
		]);
	}

    public function process(SamplesListRequest $request, $locale, $id)
    {
        $samplesList = SamplesList::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'sample_number' => 'integer',
            'buying_date' => 'required',
            'rd_delivery_date' => 'required',
        ],[
            'buying_date.required' => Lang::get('samples_list.validation.buying_date'),
            'rd_delivery_date.required' => Lang::get('samples_list.validation.rd_delivery_date'),
        ]);

        if ($validator->fails()) {
            return redirect()->route('samples_lists.edit', ['id' => $samplesList->id])
                ->withErrors($validator)
                ->withInput();
        }

        if(empty($samplesList)) {
            $samplesList = new SamplesList($request->all());
            $samplesList->save();
        } else {
            $samplesListData = $request->all();

            if(!isset($samplesListData['product_id'])) {
                $samplesListData['product_id'] = null;
            }
            if(!isset($samplesListData['qa_journal_id'])) {
                $samplesListData['qa_journal_id'] = null;
            }
            $samplesList->update($samplesListData);
        }

        if ($samplesList->qa_journal) {
            if (empty($samplesList->manifacturing_date)) {
                $samplesList->manifacturing_date = $samplesList->qa_journal->batch_date;
            }
            if (empty($samplesList->batch)) {
                $samplesList->batch = $samplesList->qa_journal->batch_number;
            }
            $samplesList->update();
        }

        $samplesPackage = $samplesList->packages()->first();

        if (!$samplesPackage) {
            $samplesPackage = new SamplesPackage();
            $samplesPackage->samples_list_id = $samplesList->id;
            $samplesPackage->comment = '';
            $samplesPackage->samples_count = 0;
            $samplesPackage->manifacturing_time = '00:00';
            $samplesPackage->save();
        }

        if (!empty($samplesList->manifacturing_time) || !empty($samplesList->samples_count)) {

            if (!empty($samplesList->manifacturing_time)) {
                $samplesPackage->manifacturing_time = $samplesList->manifacturing_time;
            }

            if (!empty($samplesList->samples_count)) {
                $samplesPackage->samples_count = $samplesList->samples_count;
            }

            $samplesPackage->save();
        }

        $request->session()->flash('status', __('samples_list.saved'));
        return redirect()->route('samples_lists.edit', $samplesList->id);
    }

    public function delete(SamplesListRequest $request, $locale, $id)
    {
        $samplesList = SamplesList::findOrFail($id);

        $samplesList->deleted = 1;
        $samplesList->save();

        $request->session()->flash('status', __('samples_list.deleted'));

        return redirect()->route('samples_list.index');
    }

    /**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function diaperWeightsJson($locale, $samplesPackageId) {
        $samplesPackage = SamplesPackage::findOrFail($samplesPackageId);
        $samplesPackage->recalculateWeights();

        $diaperWeightsArr = SamplesPackageDiaperWeights::getDatatablesDataForPackageIds([$samplesPackageId]);

        return DataTables::of($diaperWeightsArr)
                ->make();
	}

    public function analysisSamplesCount(Request $request, $locale, $samplesPackageId) {
        $samplesPackage = SamplesPackage::findOrFail($samplesPackageId);
        $analysis = $request->query->get('analysis');

		$sampleAnalyses = $this->_getSampleAnalyses($analysis, $samplesPackage->getSamplesIdsArr());

        return response()->json([
			'count' => count($sampleAnalyses)
		]);
    }

    public function analysisSamplesDelete(Request $request, $locale, $samplesPackageId) {
        $samplesPackage = SamplesPackage::findOrFail($samplesPackageId);
        $samplesIds = $request->request->get('ids');

        Sample::deleteSamplesInPackage($samplesIds, $samplesPackage);

        return response()->json([
			'success' => true
		]);
    }

    public function averageValues(Request $request, $locale, $samplesPackageId, $analysis)
    {
        $samplesPackage = SamplesPackage::findOrFail($samplesPackageId);


        $samplesIdsArr = $samplesPackage->getSamplesIdsArr();


        $analysisDefinition = AnalysisDefinition::where('slug', $analysis)->first();

		$sampleAnalysesArr = Analysis::whereIn('sample_id', $samplesIdsArr)
                ->where('enabled', true)
                ->where('analysis_definition_id', $analysisDefinition->id)
                ->get();


        $tabIndexHelper = new TabIndexHelper();
        $groupedHeaderHelper = new GroupedHeaderHelper();

        $analysisLimitsJson = AnalysisLimits::getLimitsJsonByTypeAndAnalysis('new_born', $analysis . '_averages');

        return view('samples-list/average_values', [
			'analysis'	=> $analysis,
            'samplesIdsArr' => $samplesIdsArr,
            'analysisDefinition' => $analysisDefinition,
            'tabIndexHelper' => $tabIndexHelper,
            'sampleAnalyses' => $sampleAnalysesArr,
            'samplesPackage' => $samplesPackage,
            'groupedHeaderHelper' => $groupedHeaderHelper,
            'analysisLimitsJson' => $analysisLimitsJson,
		]);
    }

    private function calculateAndReturnAverageValuesArr($analysisSlug, $samplesPackageId, $table = null, $attribute = null, $value = null) {
        $samplesPackage = SamplesPackage::findOrFail($samplesPackageId);

        $analysisDefinition = AnalysisDefinition::where('slug', $analysisSlug)->first();
        $attributesProperties = $analysisDefinition->getAttributesPropertiesFlat();

        $samplesIdsArr = $samplesPackage->getAnalysisSamplesIdsArr($analysisSlug, $attribute, $value);
        $analyses = Analysis::whereIn('sample_id', $samplesIdsArr)
                ->where('enabled', true)
                ->where('analysis_definition_id', $analysisDefinition->id)
                ->get();

        $analysesAverageValues = [];
        $analysesMeaningfulValues = [];

        if (count($analyses) === 0) {
            $analysesAverageValues = $analysisDefinition->getEmptyAttributesArray($table);
            $analysesAverageValues['id'] = $analysisSlug;
            return $analysesAverageValues;
        }

		foreach($analyses as $analysis) {
            $sampleWeight = $analysis->sample->weight;
            $analysisArr = $analysis->getAttributesArray($table);

            foreach($analysisArr as $attribute => $value) {
                if(isset($attributesProperties[$attribute]['type'])) {

                    if(!isset($analysesAverageValues[$attribute])) {
                        if($attributesProperties[$attribute]['type'] == 'number') {
                            $analysesAverageValues[$attribute] = null;
                            $analysesMeaningfulValues[$attribute] = 0;
                        } else if($attributesProperties[$attribute]['type'] == 'textarea') {
                            $analysesAverageValues[$attribute] = '';
                        } else if($attribute == 'completion__analysis_complete') {
                            $analysesAverageValues[$attribute] = $value;
                        } else {
                            $analysesAverageValues[$attribute] = 'N/A';
                        }
                    }

                    if($attributesProperties[$attribute]['type'] == 'number') {
                        if (is_numeric($value)) {
                            $analysesAverageValues[$attribute] += (float) $value;
                            $analysesMeaningfulValues[$attribute]++;
                        }
                    } else if($attributesProperties[$attribute]['type'] == 'textarea') {
                        if (!empty($value)) {
                            $analysesAverageValues[$attribute] .= '(' . $sampleWeight . 'g) ' . $value . "\n";
                        } else {
                            $analysesAverageValues[$attribute] .= '';
                        }
                    } else if($attribute == 'completion__analysis_complete') {
                        if ($value !== 'completed') {
                            $analysesAverageValues[$attribute] = 'not_completed';
                        }
                    } else {
                        $analysesAverageValues[$attribute] = 'N/A';
                    }
                }
            }
		}

        $samplesCount = count($samplesIdsArr);
        foreach($analysesAverageValues as $attribute => $value) {
            if(!is_numeric($value)) {
                continue;
            }

            if ($analysesMeaningfulValues[$attribute] > 0) {
                $analysesAverageValues[$attribute] /= $analysesMeaningfulValues[$attribute];
                $analysesAverageValues[$attribute] = round($analysesAverageValues[$attribute], 2);
            }
        }

        $analysesAverageValues['id'] = $analysisSlug;

        return $analysesAverageValues;
    }

    private function calculateAndReturnAverageValues($analysisSlug, $samplesPackageId, $table = null, $attribute = null, $value = null) {
        $analysesAverageValues = $this->calculateAndReturnAverageValuesArr($analysisSlug, $samplesPackageId, $table, $attribute, $value);

        return DataTables::of([$analysesAverageValues])->make();
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function audit($locale, $id)
    {
        $samplesList = SamplesList::findOrFail($id);
        $auditLog = $samplesList->audits()->with('user')->get();

        $packages = $samplesList->packages()->get();

        foreach($packages as $package) {
            $auditLogTmp = $package->audits()->with('user')->get();
            $auditLog = $auditLog->merge($auditLogTmp);
        }

        $auditLog = $auditLog->sortByDesc(function ($item, $key) {
            return $item->created_at;
        });

        return view('samples-list/audit', [
			'samplesList' => $samplesList,
            'auditLog' => $auditLog,
		]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function auditPackage($locale, $id)
    {
        $samplesPackage = SamplesPackage::findOrFail($id);
        $auditLog = $samplesPackage->audits()->with('user')->get();

        $samples = $samplesPackage->samples()->get();

        foreach($samples as $sample) {
            $auditLogTmp = $sample->audits()->with('user')->get();
            $auditLog = $auditLog->merge($auditLogTmp);
        }

        $auditLog = $auditLog->sortByDesc(function ($item, $key) {
            return $item->created_at;
        });

        return view('samples-list/audit_package', [
			'samplesPackage' => $samplesPackage,
            'auditLog' => $auditLog,
		]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function auditAnalysis($locale, $samplesPackageId, $analysis)
    {
        $samplesPackage = SamplesPackage::findOrFail($samplesPackageId);

        $analysisDefinition = AnalysisDefinition::where('slug', $analysis)->first();

        $sampleAnalyses = $this->_getSampleAnalyses($analysis, $samplesPackage->getSamplesIdsArr());

        if($analysisDefinition->slug == 'color_stability' && count($sampleAnalyses) > 1) {
            $sampleAnalyses = [$sampleAnalyses[0]];
        }



        $auditLog = null;

        foreach($sampleAnalyses as $sampleAnalysis) {
            $auditLogTmp = $sampleAnalysis->audits()->with('user')->get();
            if(!$auditLog) {
                $auditLog = $auditLogTmp;
            } else {
                $auditLog = $auditLog->merge($auditLogTmp);
            }

            $attributes = $sampleAnalysis->attributes()->get();

            foreach($attributes as $attribute) {
                $auditLogTmp = $attribute->audits()->with('user')->get();
                $auditLog = $auditLog->merge($auditLogTmp);
            }
        }




        $auditLog = $auditLog->sortByDesc(function ($item, $key) {
            return $item->created_at;
        });

        return view('samples-list/audit_analysis', [
			'analysis' => $analysis,
            'samplesPackage' => $samplesPackage,
            'auditLog' => $auditLog,
		]);
    }

    /**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function assignmentsJson($locale, $samplesPackageId) {
        $samplesPackage = SamplesPackage::findOrFail($samplesPackageId);

        if ($samplesPackage->samples_assigned) {
            return response()->json([
                'success' => false,
                'error' => Lang::trans('samples_list.error.samples_already_assigned'),
            ]);
        }

        if (!$samplesPackage->hasMinimumCountForAssignments()) {
            return response()->json([
                'success' => false,
                'error' => Lang::trans('samples_list.error.not_enough_samples', [
                    'min' => $samplesPackage->getMinimumCountForAssignments(),
                    'has' => $samplesPackage->samples()->count(),
                ]),
            ]);
        }

        return response()->json([
			'success' => true,
            'data' => $samplesPackage->getAssignmentsArr(),
            'analyses_names' => AnalysisDefinition::getNamesArr(),
		]);
	}

    public function assignmentsPost(Request $request, $locale, $samplesPackageId) {
        $samplesPackage = SamplesPackage::findOrFail($samplesPackageId);

        if ($samplesPackage->samples_assigned) {
            return response()->json([
                'success' => false,
                'error' => Lang::trans('samples_list.error.samples_already_assigned'),
            ]);
        }

		$dataArr = $request->request->get('data');

        foreach ($dataArr as $analysisSlug => $samplesArr) {
            $analysisDefinition = AnalysisDefinition::where('slug', $analysisSlug)->first();

            if (empty($analysisDefinition)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Analysis not found!',
                ]);
            }

            $samplesIdsArr = [];
            foreach ($samplesArr as $sampleArr) {
                if (!is_numeric($sampleArr['id'])) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Wrong value! ' . $sampleArr['id'],
                    ]);
                }

                $samplesIdsArr[] = (int) $sampleArr['id'];
            }

            $this->assignSamplesToAnalysis($samplesIdsArr, $samplesPackage, $analysisDefinition);

            if ($analysisSlug === 'absorbtion_before_leakage') {
                $ablSampleAnalyses = $this->_getSampleAnalyses($analysisDefinition->slug, $samplesIdsArr);

                $i = 0;
                foreach ($ablSampleAnalyses as $ablSampleAnalysis) {
                    if ($i < 5) {
                        $ablSampleAnalysis->setAttributeValue('table_1__mannequin_position', 'standing');
                    } elseif ($i>=5 && $i<10) {
                        $ablSampleAnalysis->setAttributeValue('table_1__mannequin_position', 'belly/back');
                    } elseif ($i>=10 && $i<15) {
                        $ablSampleAnalysis->setAttributeValue('table_1__mannequin_position', 'sideways');
                    }
                    $i++;
                }
            }
        }

        $samplesPackage->samples_assigned = true;
        $samplesPackage->save();

		return response()->json([
			'success' => true
		]);
	}

    /**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function enabledAnalysesJson($locale, $samplesPackageId) {
        $samplesPackage = SamplesPackage::findOrFail($samplesPackageId);

        return response()->json([
			'success' => true,
            'data' => $samplesPackage->getEnabledAnalyses(),
		]);
	}

    public function enabledAnalysesPost(Request $request, $locale, $samplesPackageId) {
        $samplesPackage = SamplesPackage::findOrFail($samplesPackageId);

		$dataArr = $request->request->get('enabled_analyses');

        $analysisDefinitions = AnalysisDefinition::all('slug');
        $slugs = [];
        foreach ($analysisDefinitions as $analysisDefinition) {
            $slugs[] = $analysisDefinition->slug;
        }

        if (empty($dataArr)) {
            $dataArr = $slugs;
        }

        $newEnabledAnalyses = [];
        foreach($dataArr as $analysisSlug) {
            if (in_array($analysisSlug, $slugs)) {
                $newEnabledAnalyses[] = $analysisSlug;
            }
        }

        $samplesPackage->setEnabledAnalyses($newEnabledAnalyses);
        $samplesPackage->update();

		return response()->json([
			'success' => true
		]);
	}
}

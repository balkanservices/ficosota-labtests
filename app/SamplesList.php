<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\AnalysisLimits;

class SamplesList extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = ['name', 'rd_delivery_date', 'buying_date',
        'analysis_end_date', 'urgent_analysis_samples', 'priority',
        'manifacturing_date', 'batch', 'manifacturing_time', 'region',
        'market',
        'concept2', 'country_of_origin', 'manifacturer',
        'pictograms', 'breathable_sheet', 'lotion_smell',
        'lotion_composition', 'status', 'comment', 'qa_journal_id', 'product_id',
        'sample_number',
        'elastic_elements', 'analysis_start_date', 'analysis_planned_end_date',
        'samples_count',
//        'size', 'weight_range', 'samples_count', 'concept', 'subconcept', 'product',
    ];

    public function qa_journal()
    {
        return $this->belongsTo('App\QaJournal');
    }

    public function packages()
    {
        return $this->hasMany('App\SamplesPackage');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function recalculateWeights()
    {
        $packages = $this->packages()->get();

        foreach($packages as $package) {
            $package->recalculateWeights();
        }
    }

    public function getPackageIds() {
        $packageIds = [];
        $packages = $this->packages()->get();
        foreach($packages as $package) {
            $packageIds[] = $package->id;
        }

        return $packageIds;
    }

    public function getSamplesIdsArr() {
        $samplesIdsArr = [];
        $packages = $this->packages()->get();
        foreach($packages as $package) {
            $samples = $package->samples()->get();
            foreach($samples as $sample) {
                $samplesIdsArr[] = $sample->id;
            }
        }
        return $samplesIdsArr;
    }

    public function getColorStabilitySamplesIdsArr() {
        return $this->getAnalysisSamplesIdsArr('color_stability');
    }

    public function getAnalysisSamplesIdsArr($analysisSlug) {
        $samplesIdsArr = [];
        $packages = $this->packages()->get();
        foreach($packages as $package) {
            $samples = $package->samples()->get();
            foreach($samples as $sample) {
                foreach($sample->getEnabledAnalyses() as $analysis) {
                    $definition = $analysis->definition()->first();
                    if($definition->slug == $analysisSlug) {
                        $samplesIdsArr[] = $sample->id;
                    }
                }
            }
        }
        return $samplesIdsArr;
    }

    public function getAnalysisLimitsDiapersWeights() {
        if(!$this->qa_journal) {
            return '{}';
        };

        if(!$this->qa_journal->recipe) {
            return '{}';
        };

        if(!$this->qa_journal->recipe->product) {
            return '{}';
        };

        $analysis_limits_type = $this->qa_journal->recipe->product->analysis_limits;
        return AnalysisLimits::getDiaperWeightsLimitsJsonByTypeAndUsedMaterials($analysis_limits_type, $this);
    }

    public function getAnalysisLimits($analysis) {
        if(!$this->qa_journal) {
            return '{}';
        };

        if(!$this->qa_journal->recipe) {
            return '{}';
        };

        if(!$this->qa_journal->recipe->product) {
            return '{}';
        };

        $analysis_limits_type = $this->qa_journal->recipe->product->analysis_limits;
        return AnalysisLimits::getLimitsJsonByTypeAndAnalysis($analysis_limits_type, $analysis);
    }

    public function getPackageName() {
        if($this->qa_journal) {
            return $this->qa_journal->getName();
        } elseif ($this->product_id) {
            return $this->product()->first()->getName();
        }

        return '---';
    }

    public function getPackageInfo() {
        $info = [];

        if (!empty($this->market)) {
            $info[] = $this->market;
        }

        $product = null;
        if ($this->qa_journal && $this->qa_journal->recipe && $this->qa_journal->recipe->product) {
            $product = $this->qa_journal->recipe->product;
        } elseif ($this->product_id) {
            $product = $this->product()->first();
        }

        if ($product) {
            if (!empty($product->brand)) {
                $info[] = $product->brand;
            }

            if (!empty($product->conception)) {
                $info[] = $product->conception;
            }

            if (!empty($product->size)) {
                $info[] = $product->size;
            }
        }

        if (!empty($this->batch)) {
            $info[] = ' / ' . $this->batch;
        }

        if (!empty($this->manifacturing_date)) {
            $info[] = ' / ' . $this->manifacturing_date;
        }

        return implode(' ', $info);
    }

    public function getDistinct($field) {
        $filteredResults = [];
        $results = self::distinct()->get([$field]);

        foreach ($results as $result) {
            $filteredResults[] = $result[$field];
        }

        return $filteredResults;
    }

    public static function listOrSearch($searchText, $pageSize, $filterParams) {
        $sampleListsQuery = SamplesList::
                select('samples_lists.*')
                ->leftJoin('products', 'products.id', '=', 'samples_lists.product_id')
                ->leftJoin('qa_journals', 'qa_journals.id', '=', 'samples_lists.qa_journal_id')
                ->leftJoin('recipes', 'recipes.id', '=', 'qa_journals.recipe_id')
                ->leftJoin('products as products2', 'products2.id', '=', 'recipes.product_id')
                ->where('samples_lists.deleted', 0);

        if (!empty($searchText)) {
            $sampleListsQuery->where(function($query) use ($searchText) {
                $searchTextEscaped  = addcslashes($searchText, '%_');

                $columns = ['manifacturer', 'region', 'market', 'pictograms',
                    'lotion_smell', 'lotion_composition', 'status', 'comment'];

                foreach ($columns as $column ) {
                    $query->orWhere('samples_lists.' . $column, 'LIKE', '%' . $searchTextEscaped . '%');
                }

                $columns = ['type', 'conception', 'sub_conception', 'size', 'brand',
                'kg_range'];

                foreach ($columns as $column ) {
                    $query->orWhere('products.' . $column, 'LIKE', '%' . $searchTextEscaped . '%');
                    $query->orWhere('products2.' . $column, 'LIKE', '%' . $searchTextEscaped . '%');
                }
            });

        }


            foreach ($filterParams as $column => $value) {
                if ($column == 'sort') {
                    continue;
                }
                $valueEscaped  = '%' . addcslashes($value, '%_'). '%';
                if (in_array($column, ['brand', 'conception', 'size', 'type'] )) {
                    $sampleListsQuery->where(function($query) use ($column, $valueEscaped) {
                        $query->where('products.' . $column, 'LIKE', $valueEscaped);
                        $query->orWhere('products2.' . $column, 'LIKE', $valueEscaped);
                    });
                } else {
                    $sampleListsQuery->where('samples_lists.' . $column, 'LIKE', $valueEscaped);
                }
            }


        if (empty($filterParams['sort'])) {
            $sampleListsQuery->orderBy(\DB::raw('ISNULL(samples_lists.analysis_end_date)'), 'desc')
                ->orderBy('samples_lists.analysis_end_date', 'desc')
                ->orderBy('samples_lists.id', 'desc');
        } else {
            foreach ($filterParams['sort'] as $column => $sortValue) {
                if (in_array($column, ['brand', 'conception', 'size', 'type'] )) {
                    $sampleListsQuery->orderBy(\DB::raw('GREATEST(IFNULL(products.' . $column . ', ""), IFNULL(products2.' . $column . ', ""))'), $sortValue)
                        ->orderBy('samples_lists.analysis_end_date', 'desc')
                        ->orderBy('samples_lists.id', 'desc');
                } else {
                    if (in_array($column, ['sample_number', 'batch'])) {
                        $sampleListsQuery
                        ->orderBy(\DB::raw('samples_lists.' . $column . '+0'), $sortValue)
                            ->orderBy('samples_lists.analysis_end_date', 'desc')
                            ->orderBy('samples_lists.id', 'desc');
                    } else {
                        $sampleListsQuery
                            ->orderBy('samples_lists.' . $column, $sortValue)
                            ->orderBy('samples_lists.analysis_end_date', 'desc')
                            ->orderBy('samples_lists.id', 'desc');
                    }

                }
            }
        }

        return $sampleListsQuery->paginate($pageSize);
    }

    public function getLinkedProduct() {
        if (
            $this->qa_journal
            && $this->qa_journal->recipe
            && $this->qa_journal->recipe->product
        ) {
            return $this->qa_journal->recipe->product;
        } else{
            $sampleListProduct = $this->product()->first();
            if ($sampleListProduct) {
                return $sampleListProduct;
            }
        }

        return null;
    }

    public static function getDistinctValues($field) {
        $filteredResults = [];
        $results = self::distinct()->where('deleted', '=', 0)->get([$field]);

        foreach ($results as $result) {
            if (empty($result[$field])) {
                continue;
            }
            $filteredResults[] = $result[$field];
        }

        return $filteredResults;
    }

    public static function getConnectedProductsDistinctValues($column) {
        $filteredResults = [];
        $results = self::
            select(\DB::raw('GREATEST(IFNULL(products.' . $column . ', ""), IFNULL(products2.' . $column . ', "")) as column_' . $column))
            ->distinct()
            ->leftJoin('products', 'products.id', '=', 'samples_lists.product_id')
            ->leftJoin('qa_journals', 'qa_journals.id', '=', 'samples_lists.qa_journal_id')
            ->leftJoin('recipes', 'recipes.id', '=', 'qa_journals.recipe_id')
            ->leftJoin('products as products2', 'products2.id', '=', 'recipes.product_id')
            ->where('samples_lists.deleted', 0)
            ->get();

        foreach ($results as $result) {
            if (empty($result['column_' . $column])) {
                continue;
            }

            $filteredResults[] = $result['column_' . $column];
        }

        return $filteredResults;
    }

    public static function getFilterParams($params, $columns) {
        $filterParams = [];

        foreach ($params as $param => $value) {
            if (
                self::isParamInColumns($param, $columns)
            ) {
                $filterParams[$param] = $value;
            }

            if ($param == 'sort') {
                $sortComponents = explode('__', $value);
                if (
                    self::isParamInColumns($sortComponents[0], $columns)
                    && in_array($sortComponents[1], ['a_z', 'z_a'])
                ) {
                    $filterParams['sort'] = [
                        $sortComponents[0] => (($sortComponents[1] === 'a_z' )? 'ASC' : 'DESC'),
                    ];
                }
            }
        }

        return $filterParams;
    }

    public static function isParamInColumns($param, $columns) {
        foreach ($columns as $column) {
            if ($param == $column['paramName']) {
                return true;
            }
        }

        return false;
    }

    public static function getColumnValues($param, $columns) {
        foreach ($columns as $column) {
            if ($param == $column['paramName']) {
                return $column['values'];
            }
        }

        return [];
    }

    public function areAllPackageAnalysesCompleted() {
        $packages = $this->packages()->get();

        $areAllPackageAnalysesCompleted = true;
        foreach ($packages as $package) {
            if (!$package->areAllEnabledAnalysesCompleted()) {
                $areAllPackageAnalysesCompleted = false;
            }
        }

        if ($areAllPackageAnalysesCompleted && !$packages->isEmpty()) {
            return true;
        }

        return false;
    }
}

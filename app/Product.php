<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;

class Product extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = ['brand', 'sub_brand', 'conception', 'sub_conception', 'size',
        'kg_range', 'diaper_count_in_package', 'analysis_limits', 'type', 'machine',
//        'region', 'market', 'year', 'quarter', 'country_of_origin',
//        'producer', 'date_of_production', 'batch', 'excel_sample_number',
//        'excel_sheet', 'date_bought', 'date_supplied_to_rd_center',
//        'pictograms', 'product', 'breathable_sheet',
//        'status', 'comment', 'conception2',
//        'date_finished_sample_analyses', 'samples_for_urgent_analyses',
//        'samples_priorities', 'time_of_production', 'lotion_smell',
//        'lotion_package_ingredients',

    ];

    protected static $typesArr = [
        'diapers', 'eco_diapers', 'bio_diapers', 'pants'
    ];

    public static function getMachines() {
        return [
            'Fameccanica',
            'GDM'
        ];
    }

    public function autodetectMachine() {

        if (stripos($this->brand, 'pufies') === FALSE) {
            $this->machine = null;
            return;
        }

        if (in_array(strtolower($this->size), [
            'midi',
            'maxi',
            'maxi+',
        ])) {
            $this->machine = 'Fameccanica';
        } elseif (in_array(strtolower($this->size), [
            'new born',
            'newborn',
            'mini',
            'junior',
            'extra large',
        ])) {
            $this->machine = 'GDM';
        } else {
            $this->machine = null;
        }
    }

    public function recipes()
    {
        return $this->hasMany('App\Recipe')->where('deleted', 0);
    }

    public function samplesLists()
    {
        return $this->hasMany('App\SamplesList')->where('deleted', 0);
    }

    public static function getNamesArray($includeEmpty = true) {
        $namesArr = [];

        if($includeEmpty) {
            $namesArr = ['' => ''];
        }

        $products = self::where('deleted', 0)->orderBy('id', 'desc')->get();

        foreach($products as $product) {
            $namesArr[$product->id] = $product->getName();
        }

        return $namesArr;
    }

    public static function getPufiesNamesArray($includeEmpty = true) {
        $namesArr = [];

        if($includeEmpty) {
            $namesArr = ['' => ''];
        }

        $products = self::where('deleted', 0)
            ->where('brand', 'like', '%pufies%')
            ->where(function ($query) {
                $query->where('machine', '=', 'Fameccanica')
                    ->orWhere('machine', '=', 'GDM');
            })
            ->orderBy('id', 'desc')->get();

        foreach($products as $product) {
            $namesArr[$product->id] = $product->getName();
        }

        return $namesArr;
    }

    public static function getCompetitiveProductsNamesArray($includeEmpty = true) {
        $namesArr = [];

        if($includeEmpty) {
            $namesArr = ['' => ''];
        }

        $products = self::where('deleted', 0)
            ->where('brand', 'not like', '%pufies%')
            ->orderBy('id', 'desc')->get();

        foreach($products as $product) {
            $namesArr[$product->id] = $product->getName();
        }

        return $namesArr;
    }

    public static function processExcelSheet($sheet) {
        $productsImported = 0;

        $mapping = self::getExcelSheetRowMapping($sheet->getHeading());
        $productsArr = $sheet->all();

        foreach( $productsArr as $productArr ) {
            if(empty($productArr['marka']) || isset($productArr['topsheet'])) {
                continue;
            }
            $product = self::processProductCollection($productArr, $mapping);
            $product->excel_sheet = $sheet->getTitle();
            $product->deleted = 0;

            if(!self::productExists($product)) {
                $product->save();
                $productsImported++;
            }
        }

        return $productsImported;
    }

    private static function getExcelSheetRowMapping($headingArr) {
        return [
            'na_mostrata' => 'excel_sample_number',
            'data_na_predostavyane_v_rd_centre' => 'date_supplied_to_rd_center',
            'data_na_zakupuvane' => 'date_bought',
            'data_na_proizvodstvo' => 'date_of_production',
            'partida' => 'batch',
            'region' => 'region',
            'pazar' => 'market',
            'marka' => 'brand',
            'razmer' => 'size',
            'range_kg' => 'kg_range',
            'kontseptsiya' => 'conception',
            'podkontseptsiya' => 'sub_conception',
            'kontseptsiya_1' => 'conception2',
            'broy_peleni_v_paket' => 'diaper_count_in_package',
            'strana_na_proizkhod' => 'country_of_origin',
            'proizvoditel' => 'producer',
            'piktogrami' => 'pictograms',
            'produkt' => 'product',
            'breathable_sheettsbs' => 'breathable_sheet',
            'status' => 'status',
            'komentar' => 'comment',
            'data_na_zavrshvane_na_mostrenite_analizi' => 'date_finished_sample_analyses',
            'losion_v_pelenata_miris' => 'lotion_smell',
            'sstav_na_losiona_ot_opakovkata' => 'lotion_package_ingredients',
            'chas_na_proizvodstvo' => 'time_of_production',
            'prioritet_na_predostavenite_mostri' => 'samples_priorities',
            'produkt' => 'type',
        ];
    }

    private static function processProductCollection($productArr, $mapping) {
        $product = new Product();

        foreach($productArr as $name => $value) {
            if($value === null) {
                continue;
            }

            if($value instanceof Carbon) {
                $product->{$mapping[$name]} = $value->format('Y-m-d');
            } else {
                if ($name == 'produkt') {
                    switch ($value) {
                        case 'пелени':
                            $value = 'diapers';
                            break;
                        case 'eco diapers':
                            $value = 'eco_diapers';
                            break;
                    }
                } elseif ($name == 'na_mostrata') {
                    $value = (int) $value;
                }

                $product->{$mapping[$name]} = $value;
            }
        }

        return $product;
    }

    private static function productExists(Product $product) {
        return self::where('brand', '=', $product->brand)
                ->where('size', '=', $product->size)
                ->where('type', '=', $product->type)
                ->where('conception', '=', $product->conception)
                ->where('excel_sheet', '=', $product->excel_sheet)
                ->where('date_of_production', '=', $product->date_of_production)
                ->where('sub_conception', '=', $product->sub_conception)
                ->where('deleted', '=', $product->deleted)
                ->count();
    }

    public function getName() {
        $columns = ['type', 'brand', 'sub_brand', 'conception', 'size', 'kg_range', 'machine'];
        $nameArr = [];
        foreach($columns as $column) {
            if(!empty($this->{$column})) {
                if ($column == 'type') {
                    $nameArr[] = self::getTypeName($this->{$column});
                } else {
                    $nameArr[] = $this->{$column};
                }
            }
        }

        if(!empty($this->excel_sheet)) {
            $nameArr[] = '(' . $this->excel_sheet . ')';
        }
        return implode(" ", $nameArr);
    }

    public function getNameForRecipes() {
        $columns = ['brand', 'conception', 'size'];
        $nameArr = [];
        foreach($columns as $column) {
            if(!empty($this->{$column})) {
                if ($column == 'type') {
                    $nameArr[] = self::getTypeName($this->{$column});
                } else {
                    $nameArr[] = $this->{$column};
                }
            }
        }

        if(!empty($this->excel_sheet)) {
            $nameArr[] = '(' . $this->excel_sheet . ')';
        }
        return implode(" ", $nameArr);
    }

    public static function getTypeOptions() {
        return self::$typesArr;
    }

    public static function getTypeName($type) {
        if (in_array($type, self::$typesArr)) {
            return Lang::get('products.types.' . $type);
        }

        return $type;
    }

    public function getDistinct($field) {
        $filteredResults = [];
        $results = self::distinct()->get([$field]);

        foreach ($results as $result) {
            $filteredResults[] = $result[$field];
        }

        return $filteredResults;
    }

    public static function getDistinctValues($field) {
        $filteredResults = [];
        $results = self::distinct()->where('deleted', '=', 0)->get([$field]);

        foreach ($results as $result) {
            $filteredResults[] = $result[$field];
        }

        return $filteredResults;
    }

    public static function getActiveRecipesProductConcepts() {
        return DB::select('SELECT
                DISTINCT(CONCAT(COALESCE(p.brand, \'\'), \' \', COALESCE(p.conception, \'\'))) as concept
            FROM
                recipes r
                    INNER JOIN
                (SELECT
                    product_id, MAX(revision_number) AS max_revision_number
                FROM
                    recipes
                WHERE
                    deleted = 0
                GROUP BY product_id) AS r2 ON r.product_id = r2.product_id
                    AND r.revision_number = r2.max_revision_number
                    INNER JOIN
                products p ON r.product_id = p.id
            WHERE
                r.deleted = 0
                AND p.deleted = 0
                AND p.machine IS NOT NULL
        ');
    }

    public static function getActiveRecipesMachinesForConcept($concept) {
        return DB::select('SELECT
                DISTINCT(p.machine)
            FROM
                recipes r
                    INNER JOIN
                (SELECT
                    product_id, MAX(revision_number) AS max_revision_number
                FROM
                    recipes
                WHERE
                    deleted = 0
                GROUP BY product_id) AS r2 ON r.product_id = r2.product_id
                    AND r.revision_number = r2.max_revision_number
                    INNER JOIN
                products p ON r.product_id = p.id
            WHERE
                r.deleted = 0
                AND p.deleted = 0
                AND CONCAT(COALESCE(p.brand, \'\'), \' \', COALESCE(p.conception, \'\')) = :concept
                AND machine IS NOT NULL
            ORDER BY machine ASC
            ',
            [
                'concept' => $concept
            ]
        );
    }

    public static function getNotActiveRecipesProductConcepts() {
        return DB::select('SELECT
                DISTINCT(CONCAT(COALESCE(p.brand, \'\'), \' \', COALESCE(p.conception, \'\'))) as concept
            FROM
                recipes r
                    INNER JOIN
                (SELECT
                    product_id, MAX(revision_number) AS max_revision_number
                FROM
                    recipes
                WHERE
                    deleted = 0
                GROUP BY product_id) AS r2 ON r.product_id = r2.product_id

                INNER JOIN
                products p ON r.product_id = p.id
            WHERE
                r.deleted = 0
                AND p.deleted = 0
                AND p.machine IS NOT NULL
                AND (r.revision_number != r2.max_revision_number OR r.revision_number IS NULL)
        ');
    }

    public static function getNotActiveRecipesMachinesForConcept($concept) {
        return DB::select('SELECT
                DISTINCT(p.machine)
            FROM
                recipes r
                    INNER JOIN
                (SELECT
                    product_id, MAX(revision_number) AS max_revision_number
                FROM
                    recipes
                WHERE
                    deleted = 0
                GROUP BY product_id) AS r2 ON r.product_id = r2.product_id

                    INNER JOIN
                products p ON r.product_id = p.id
            WHERE
                r.deleted = 0
                AND p.deleted = 0
                AND CONCAT(COALESCE(p.brand, \'\'), \' \', COALESCE(p.conception, \'\')) = :concept
                AND machine IS NOT NULL
                AND (r.revision_number != r2.max_revision_number OR r.revision_number IS NULL)
            ORDER BY machine ASC
            ',
            [
                'concept' => $concept
            ]
        );
    }

    public static function getNotActiveRecipesSizesForConceptAndMachine($concept, $machine) {
        return DB::select('SELECT
                DISTINCT(p.size)
            FROM
                recipes r
                    INNER JOIN
                (SELECT
                    product_id, MAX(revision_number) AS max_revision_number
                FROM
                    recipes
                WHERE
                    deleted = 0
                GROUP BY product_id) AS r2 ON r.product_id = r2.product_id

                    INNER JOIN
                products p ON r.product_id = p.id
            WHERE
                r.deleted = 0
                AND p.deleted = 0
                AND CONCAT(COALESCE(p.brand, \'\'), \' \', COALESCE(p.conception, \'\')) = :concept
                AND machine = :machine
                AND (r.revision_number != r2.max_revision_number OR r.revision_number IS NULL)
            ORDER BY machine ASC
            ',
            [
                'concept' => $concept,
                'machine' => $machine
            ]
        );
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SamplesPackage;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class SamplesPackageDiaperWeights extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = [
        'samples_package_id', 'average', 'min', 'max', 'standard_deviation', 'delta',
        'count',
    ];

    public function package()
    {
        return $this->belongsTo('App\SamplesPackage');
    }

    public static function getDatatablesDataForPackageIds($packageIds)
    {
        $datatablesData = [];
        foreach($packageIds as $packageId) {
            $package = SamplesPackage::findOrFail($packageId);
            $packageDiaperWeights = self::where('samples_package_id', '=', $packageId)->first();
            $packageDiaperWeightsArr = $packageDiaperWeights->toArray();
            $packageDiaperWeightsArr['package_manifacturing_time'] = $package->manifacturing_time;
            $datatablesData[] = $packageDiaperWeightsArr;
        }

        return $datatablesData;
    }
}

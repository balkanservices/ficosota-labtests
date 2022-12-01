<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class AnalysisAttribute extends Model implements AuditableContract
{
    use Auditable;

    public function analysis()
    {
        return $this->belongsTo('App\Analysis', 'analysis_id');
    }
}

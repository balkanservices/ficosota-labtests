<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class QaJournalIngredient extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = ['qa_journal_id', 'ingredient_id', 'option_id',
        'option_batch_number', 'option_fs_batch_number'];

    public function ingredient()
    {
        return $this->hasOne('App\RecipeIngredient', 'id', 'ingredient_id');
    }

    public function option()
    {
        return $this->hasOne('App\RecipeIngredientOption', 'id', 'option_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ImportMunfiq extends Model
{
    protected $table = 'import_munfiq';

    protected $guarded = [];

    public function scopeOrderGang(Builder $query): Builder
    {
        return $query
            ->orderByRaw("
                CAST(COALESCE(REGEXP_SUBSTR(sheet_name, '[0-9]+'), 0) AS UNSIGNED)
            ")
            ->orderByRaw("
                COALESCE(REGEXP_SUBSTR(sheet_name, '[A-Za-z]+$'), '')
            ");
    }
}

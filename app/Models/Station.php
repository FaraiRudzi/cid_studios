<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Station extends Model
{
    protected $fillable = ['name', 'code', 'province'];

    public function cases(): HasMany
    {
        return $this->hasMany(CaseModel::class);
    }
}

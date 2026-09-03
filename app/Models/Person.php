<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Person extends Model
{
    protected $table = 'people';

    protected $fillable = [
        'first_name', 'surname', 'id_number', 'gender', 'email', 'phone_number', 'address',
    ];

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->surname}");
    }

    public function cases(): BelongsToMany
    {
        return $this->belongsToMany(CaseModel::class, 'case_person', 'person_id', 'case_id')
            ->withPivot('role', 'notes')
            ->withTimestamps();
    }
}

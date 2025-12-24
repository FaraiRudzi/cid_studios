<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'surname',
        'id_number',
        'address',
        'phone_number',
        'email',
    ];

    /**
     * Updated Full Name Accessor
     * Handles "UNKNOWN" cases to prevent "UNKNOWN UNKNOWN" appearing on dossiers.
     */
    protected function fullName(): Attribute
{
    return Attribute::make(
        get: function ($value, $attributes) {
            $first = $attributes['first_name'] ?? '';
            $last = $attributes['surname'] ?? '';

            if (strtoupper($first) === 'UNKNOWN' && strtoupper($last) === 'UNKNOWN') {
                return "UNIDENTIFIED PERSON";
            }
            return trim("$first $last");
        }
    );
}

    /**
     * Flag to check if the person has a real National ID or a temporary forensic placeholder.
     */
    protected function isVerified(): Attribute
{
    return Attribute::make(
        get: fn ($value, $attributes) =>
            !empty($attributes['id_number']) &&
            strtoupper($attributes['id_number']) !== 'UNKNOWN',
    );
}

    /**
     * Scope to exclude temporary records from global search results
     * when you only want to find established criminal/victim profiles.
     */
    public function scopeVerified(Builder $query): void
    {
        $query->where('id_number', 'not like', 'TEMP-%');
    }

    public function cases(): BelongsToMany
    {
        return $this->belongsToMany(
            CaseModel::class,
            'case_person',
            'person_id',
            'case_id'
        )->withPivot('role')->withTimestamps();
    }
}

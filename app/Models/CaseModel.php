<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CaseModel extends Model
{
    use HasFactory;

    protected $table = 'cases';
    
    /**
     * The attributes that are mass assignable.
     * This array gives Laravel permission to save these fields.
     */
    protected $fillable = [
        'scene_reference_number',
        'reference_number',
        'station_id',
        'photographer_id',
        'case_type',
        'circumstances',
        'cause_of_death',
    ];

    // Relationships
    public function station(): BelongsTo { return $this->belongsTo(Station::class); }
    public function photographer(): BelongsTo { return $this->belongsTo(Photographer::class); }
    public function media(): HasMany { return $this->hasMany(Media::class, 'case_id'); }
    public function people(): BelongsToMany {
        return $this->belongsToMany(Person::class, 'case_person', 'case_id', 'person_id')->withPivot('role')->withTimestamps();
    }

    // Accessors
    public function getDeceasedAttribute() { return $this->people()->wherePivot('role', 'deceased')->first(); }
    public function getAccusedAttribute() { return $this->people()->wherePivot('role', 'accused')->get(); }
    public function getInformantAttribute() { return $this->people()->wherePivot('role', 'informant')->first(); }
    public function getComplainantAttribute() { return $this->people()->wherePivot('role', 'complainant')->first(); }
}


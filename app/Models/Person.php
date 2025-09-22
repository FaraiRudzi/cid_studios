<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Person extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * This tells Laravel which columns you are allowed to fill using methods like `create()` or `update()`.
     */
    protected $fillable = [
        'first_name',
        'surname',
        'id_number',
        'address',
        'phone_number',
        'email',
    ];

    /**
     * An accessor to easily get the person's full name.
     * This creates a "virtual" attribute. You can now use `$person->full_name` in your code.
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['first_name'] . ' ' . $attributes['surname'],
        );
    }
    
    /**
     * Define the many-to-many relationship with the Case model.
     * A Person can be involved in many Cases.
     */
    public function cases(): BelongsToMany
    {
        // THE FIX IS HERE: We are explicitly defining the pivot table keys.
        return $this->belongsToMany(
            CaseModel::class,   // The related model (Or Case::class if your model is named that)
            'case_person',      // The name of the pivot table
            'person_id',        // The foreign key for the CURRENT model (Person)
            'case_id'           // The foreign key for the RELATED model (CaseModel)
        )->withPivot('role')->withTimestamps();
    }
}
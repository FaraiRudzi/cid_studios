<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany; // Add this 'use' statement

class Photographer extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'force_number',
        'first_name',
        'surname',
        'phone_number',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Define the one-to-many relationship with the Case model.
     * A Photographer can be assigned to many Cases.
     */
    public function cases(): HasMany
    {
        // This assumes your Case model is named 'CaseModel.php'
        // If it's named 'Case.php', change 'CaseModel::class' to 'Case::class'
        return $this->hasMany(CaseModel::class);
    }
}
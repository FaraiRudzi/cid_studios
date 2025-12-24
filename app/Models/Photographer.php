<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\CaseModel; // Ensure the CaseModel is imported

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
     * The attributes that should be cast.
     * Use 'hashed' to automatically hash the password before saving.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'email_verified_at' => 'datetime', // Assuming you have this column
    ];


    /**
     * Define the one-to-many relationship with the Case model.
     * A Photographer can be assigned to many Cases.
     */
    public function cases(): HasMany
    {
        return $this->hasMany(CaseModel::class, 'photographer_id'); // Added FK for clarity
    }
}

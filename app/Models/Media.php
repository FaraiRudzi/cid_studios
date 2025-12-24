<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
    'case_id',
    'type',        // This will store 'Scene of Crime', 'Post-Mortem', etc.
    'path',        // This stores the file path
    'description', // This stores the forensic description
];

   // In app/Models/Media.php
public function case()
{
    return $this->belongsTo(CaseModel::class); // Or Case::class
}
}

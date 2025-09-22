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
        'path',
        'type',
        'description',
    ];

   // In app/Models/Media.php
public function case()
{
    return $this->belongsTo(CaseModel::class); // Or Case::class
}
}
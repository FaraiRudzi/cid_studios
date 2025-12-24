<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseLog extends Model
{
    protected $fillable = ['case_id', 'user_id', 'action', 'role', 'description', 'metadata'];

    protected $casts = [
        'metadata' => 'array'
    ];

    /**
     * Resolve the actual name of the operator based on their role
     */
    public function getOperatorNameAttribute()
    {
        if ($this->role === 'PHOTOGRAPHER') {
            $photographer = Photographer::find($this->user_id);
            return $photographer ? "{$photographer->first_name} {$photographer->surname}" : 'Unknown Photographer';
        }

        // Defaults to Admin/Web User
        $user = User::find($this->user_id);
        return $user ? $user->name : 'System/Admin';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CaseModel extends Model
{
    /** A locked case can only be changed by an administrator changing its status. */
    public const LOCKED_STATUSES = ['CLOSED', 'ARCHIVED'];

    /** The only statuses a photographer may choose. */
    public const PHOTOGRAPHER_STATUSES = ['OPEN', 'PENDING_REVIEW'];

    protected $table = 'cases';

    protected $fillable = [
        'scene_reference_number',
        'reference_number',
        'station_id',
        'photographer_id',
        'case_type',
        'circumstances',
        'cause_of_death',
        'status',
        'created_by',
    ];

    /**
     * Reason supplied by an admin action (reassign / status change).
     * Read by CaseObserver and written to the audit log; never persisted on the case.
     */
    public ?string $auditReason = null;

    public function isLocked(): bool
    {
        return in_array($this->status, self::LOCKED_STATUSES, true);
    }

    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }

    public function photographer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'photographer_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'case_person', 'case_id', 'person_id')
            ->withPivot('id', 'role', 'notes')
            ->withTimestamps();
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class, 'case_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(CaseLog::class, 'case_id')->orderBy('created_at', 'desc')->orderBy('id', 'desc');
    }
}

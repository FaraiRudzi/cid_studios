<?php

namespace App\Models;

use App\Exceptions\EvidenceProtectionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Append-only audit entry. Once written it can be neither edited nor deleted
 * through the application. (Also revoke UPDATE/DELETE on this table for the
 * application's database user - see CHANGES.md.)
 */
class CaseLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'case_id', 'user_id', 'action', 'role', 'description', 'metadata', 'ip_address', 'created_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (CaseLog $log): void {
            $log->created_at ??= now();
        });

        static::updating(function (): void {
            throw new EvidenceProtectionException('Audit log entries are append-only and cannot be edited.');
        });

        static::deleting(function (): void {
            throw new EvidenceProtectionException('Audit log entries cannot be deleted.');
        });
    }

    public function case(): BelongsTo
    {
        return $this->belongsTo(CaseModel::class, 'case_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

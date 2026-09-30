<?php

namespace App\Models;

use App\Exceptions\EvidenceProtectionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Evidence media record. Immutable once written: it cannot be deleted, its
 * files/title cannot change, and hashes are set once. The only permitted
 * change is a logged soft-removal (markRemoved), which keeps the file and
 * the record for the chain of custody.
 */
class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'case_id', 'uploaded_by', 'title', 'category', 'file_path', 'file_hashes', 'file_type', 'file_size', 'description',
    ];

    protected function casts(): array
    {
        return [
            'file_path' => 'array',
            'file_hashes' => 'array',
            'removed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Media $media): void {
            if (CaseModel::query()->find($media->case_id)?->isLocked()) {
                throw new EvidenceProtectionException('Media cannot be added to a closed or archived case.');
            }
        });

        static::updating(function (Media $media): void {
            $allowed = ['removed_at', 'removed_by', 'removal_reason', 'file_hashes', 'updated_at'];

            if (array_diff(array_keys($media->getDirty()), $allowed) !== []) {
                throw new EvidenceProtectionException('Evidence media records are immutable.');
            }

            if ($media->isDirty('file_hashes') && ! in_array($media->getRawOriginal('file_hashes'), [null, '', '[]', '{}'], true)) {
                throw new EvidenceProtectionException('A recorded file hash cannot be changed.');
            }

            if ($media->isDirty('removed_at') && $media->getRawOriginal('removed_at') !== null) {
                throw new EvidenceProtectionException('This media item has already been removed.');
            }
        });

        static::deleting(function (): void {
            throw new EvidenceProtectionException('Evidence media records cannot be deleted. Use a logged removal instead.');
        });
    }

    public function getFilePaths(): array
    {
        $paths = $this->file_path;

        for ($attempt = 0; $attempt < 3 && is_string($paths); $attempt++) {
            $decoded = json_decode($paths, true);

            if (! is_string($decoded) && ! is_array($decoded)) {
                break;
            }

            $paths = $decoded;
        }

        if (! is_array($paths)) {
            $paths = [$paths];
        }

        return array_values(array_filter(array_map(function ($path): ?string {
            if (! is_string($path)) {
                return null;
            }

            $path = trim(str_replace('\\', '/', $path), " \\\"'");

            return $path !== '' ? $path : null;
        }, $paths)));
    }

    /** SHA-256 recorded for this path (null for legacy rows not yet baselined). */
    public function hashFor(string $path): ?string
    {
        $hashes = $this->file_hashes;

        return is_array($hashes) ? ($hashes[$path] ?? null) : null;
    }

    public function isRemoved(): bool
    {
        return $this->removed_at !== null;
    }

    public function markRemoved(int $userId, string $reason): void
    {
        $this->forceFill([
            'removed_at' => now(),
            'removed_by' => $userId,
            'removal_reason' => $reason,
        ])->save();
    }

    public function case(): BelongsTo
    {
        return $this->belongsTo(CaseModel::class, 'case_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function remover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'removed_by');
    }
}

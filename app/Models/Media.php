<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'case_id', 'uploaded_by', 'title', 'category', 'file_path', 'file_type', 'file_size', 'description',
    ];

    protected function casts(): array
    {
        return [
            'file_path' => 'array',
        ];
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

    public function case(): BelongsTo
    {
        return $this->belongsTo(CaseModel::class, 'case_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

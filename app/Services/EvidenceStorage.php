<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

/**
 * Single place that knows where evidence lives and how to address it.
 * Evidence sits on the private "evidence" disk and is only ever served
 * through the authorised route "cases.media.file".
 */
final class EvidenceStorage
{
    public const DISK = 'evidence';

    public const DIRECTORY = 'case-media';

    /** Explicit list on purpose: image/* would also allow SVG, which can carry scripts. */
    public const ACCEPTED_TYPES = [
        'image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/tiff',
        'image/heic', 'image/heif',
        'video/mp4', 'video/quicktime', 'video/webm', 'video/x-matroska', 'video/x-msvideo',
    ];

    public static function disk(): FilesystemAdapter
    {
        return Storage::disk(self::DISK);
    }

    /** Only relative paths inside case-media/, with no traversal, are ever trusted. */
    public static function isAcceptablePath(mixed $path): bool
    {
        return is_string($path)
            && str_starts_with($path, self::DIRECTORY.'/')
            && ! str_contains($path, '..')
            && ! str_contains($path, "\0")
            && ! str_contains($path, '\\');
    }

    public static function sha256(string $path): ?string
    {
        $disk = self::disk();

        return $disk->exists($path) ? hash_file('sha256', $disk->path($path)) : null;
    }

    public static function urlFor(Media $media, int $index): string
    {
        return route('cases.media.file', [
            'case' => $media->case_id,
            'media' => $media->getKey(),
            'index' => $index,
        ]);
    }
}

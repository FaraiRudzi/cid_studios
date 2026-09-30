<?php

namespace App\Console\Commands;

use App\Models\CaseLog;
use App\Models\Media;
use App\Services\EvidenceStorage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class EvidenceMigrateLegacyCommand extends Command
{
    protected $signature = 'evidence:migrate-legacy
        {--delete-source : Delete the old public copy of each file once the private copy is verified}';

    protected $description = 'Copy evidence from the old public disk to the private evidence disk, verify each copy by SHA-256, and record a baseline hash for legacy items';

    public function handle(): int
    {
        $public = Storage::disk('public');
        $evidence = EvidenceStorage::disk();

        $copied = 0;
        $baselined = 0;
        $problems = 0;

        Media::query()->orderBy('id')->chunkById(100, function ($items) use ($public, $evidence, &$copied, &$baselined, &$problems) {
            foreach ($items as $media) {
                $hashes = $media->file_hashes ?? [];
                $newHashes = false;
                $deletable = [];

                foreach ($media->getFilePaths() as $path) {
                    if (! EvidenceStorage::isAcceptablePath($path)) {
                        $this->error("Media #{$media->id}: unexpected path, skipped: {$path}");
                        $problems++;

                        continue;
                    }

                    if (! $evidence->exists($path)) {
                        if (! $public->exists($path)) {
                            $this->error("Media #{$media->id}: file missing from both disks: {$path}");
                            $problems++;

                            continue;
                        }

                        $stream = $public->readStream($path);
                        $evidence->writeStream($path, $stream);

                        if (is_resource($stream)) {
                            fclose($stream);
                        }

                        $copied++;
                    }

                    $targetHash = EvidenceStorage::sha256($path);
                    $sourceHash = $public->exists($path) ? hash_file('sha256', $public->path($path)) : null;

                    if ($sourceHash !== null && ! hash_equals($sourceHash, (string) $targetHash)) {
                        $this->error("Media #{$media->id}: copy does not match the original, NOT trusted: {$path}");
                        $problems++;

                        continue;
                    }

                    if (isset($hashes[$path]) && ! hash_equals($hashes[$path], (string) $targetHash)) {
                        $this->error("Media #{$media->id}: file differs from the hash recorded at upload: {$path}");
                        $problems++;

                        continue;
                    }

                    if (! isset($hashes[$path])) {
                        $hashes[$path] = $targetHash;
                        $newHashes = true;
                    }

                    if ($sourceHash !== null) {
                        $deletable[] = $path;
                    }
                }

                if ($newHashes) {
                    $media->file_hashes = $hashes;
                    $media->save();

                    CaseLog::create([
                        'case_id' => $media->case_id,
                        'user_id' => null,
                        'action' => 'EVIDENCE_BASELINED',
                        'role' => 'SYSTEM',
                        'description' => "Media #{$media->id}: moved to private storage and a baseline SHA-256 recorded. The hash at original upload was not captured for this legacy item, so integrity is proven from this point forward only.",
                        'metadata' => [
                            'media_id' => $media->id,
                            'files' => collect($hashes)->map(fn ($hash, $path) => ['path' => $path, 'sha256' => $hash])->values()->all(),
                        ],
                        'ip_address' => null,
                    ]);

                    $baselined++;
                }

                // Only after the hash is safely stored do we remove the public copy.
                if ($this->option('delete-source')) {
                    foreach ($deletable as $path) {
                        $public->delete($path);
                    }
                }
            }
        });

        $this->info("Copied: {$copied} file(s). Baselined: {$baselined} media record(s). Problems: {$problems}.");

        if (! $this->option('delete-source')) {
            $this->line('Public copies were left in place. Re-run with --delete-source once you have checked the result.');
        }

        return $problems === 0 ? self::SUCCESS : self::FAILURE;
    }
}

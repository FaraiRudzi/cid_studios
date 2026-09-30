<?php

namespace App\Console\Commands;

use App\Models\CaseLog;
use App\Models\Media;
use App\Services\EvidenceStorage;
use Illuminate\Console\Command;

class EvidenceVerifyCommand extends Command
{
    protected $signature = 'evidence:verify {--case= : Only verify the media of one case ID}';

    protected $description = 'Re-hash every evidence file and compare it with the SHA-256 recorded for it. Exits non-zero on any missing or altered file.';

    public function handle(): int
    {
        $checked = 0;
        $failures = [];
        $unbaselined = 0;

        $query = Media::query()->orderBy('id');

        if ($this->option('case')) {
            $query->where('case_id', (int) $this->option('case'));
        }

        $query->chunkById(100, function ($items) use (&$checked, &$failures, &$unbaselined) {
            foreach ($items as $media) {
                // Removed items are still checked: removal hides the record, it never deletes the file.
                foreach ($media->getFilePaths() as $path) {
                    $checked++;

                    if (! EvidenceStorage::isAcceptablePath($path) || ! EvidenceStorage::disk()->exists($path)) {
                        $failures[] = [$media->case_id, $media->id, $path, 'MISSING'];

                        continue;
                    }

                    $recorded = $media->hashFor($path);

                    if ($recorded === null) {
                        $unbaselined++;

                        continue;
                    }

                    if (! hash_equals($recorded, (string) EvidenceStorage::sha256($path))) {
                        $failures[] = [$media->case_id, $media->id, $path, 'MISMATCH'];
                    }
                }
            }
        });

        foreach ($failures as [$caseId, $mediaId, $path, $status]) {
            $alreadyLogged = CaseLog::query()
                ->where('case_id', $caseId)
                ->where('action', 'INTEGRITY_FAILURE')
                ->where('metadata->path', $path)
                ->where('metadata->status', $status)
                ->exists();

            if (! $alreadyLogged) {
                CaseLog::create([
                    'case_id' => $caseId,
                    'user_id' => null,
                    'action' => 'INTEGRITY_FAILURE',
                    'role' => 'SYSTEM',
                    'description' => "Integrity check failed for media #{$mediaId}: file is {$status}.",
                    'metadata' => ['media_id' => $mediaId, 'path' => $path, 'status' => $status],
                    'ip_address' => null,
                ]);
            }
        }

        $this->info("Checked {$checked} file(s).");

        if ($unbaselined > 0) {
            $this->warn("{$unbaselined} file(s) have no recorded hash yet. Run: php artisan evidence:migrate-legacy");
        }

        if ($failures !== []) {
            $this->error(count($failures).' integrity failure(s):');
            $this->table(['Case', 'Media', 'Path', 'Status'], $failures);

            return self::FAILURE;
        }

        $this->info('All hashed evidence files match their recorded hashes.');

        return self::SUCCESS;
    }
}

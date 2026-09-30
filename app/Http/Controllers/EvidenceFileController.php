<?php

namespace App\Http\Controllers;

use App\Models\CaseModel;
use App\Models\Media;
use App\Services\EvidenceStorage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class EvidenceFileController extends Controller
{
    public function __invoke(Request $request, CaseModel $case, Media $media, int $index): BinaryFileResponse
    {
        $user = $request->user();

        abort_unless($user !== null, 401);
        abort_unless($user->canAccessCase($case), 403);
        abort_unless((int) $media->case_id === (int) $case->getKey(), 404);
        abort_if($media->isRemoved(), 410, 'This item was removed from the case.');

        $path = $media->getFilePaths()[$index] ?? null;

        abort_unless(EvidenceStorage::isAcceptablePath($path), 404);
        abort_unless(EvidenceStorage::disk()->exists($path), 404);

        // BinaryFileResponse supports HTTP Range requests, so videos can seek.
        $response = response()->file(EvidenceStorage::disk()->path($path), [
            'X-Content-Type-Options' => 'nosniff',
            'Content-Security-Policy' => 'sandbox',
        ]);

        // BinaryFileResponse defaults to "public"; evidence must never be cached by shared caches.
        $response->setPrivate();
        $response->headers->addCacheControlDirective('no-store');

        return $response;
    }
}

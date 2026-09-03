<?php

use App\Filament\Resources\CaseResource\Pages\ViewCase;
use App\Models\CaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/admin/cases/{case}/exhibit/preview', function (CaseModel $case) {
    abort_unless(Auth::user()?->role === 'ADMIN', 403);

    $selectedPhotos = array_values(array_filter(explode(',', (string) request('photos'))));
    $exhibits = ViewCase::getExhibitData($case, $selectedPhotos);

    return view('filament.pages.case-exhibit-report', [
        'case' => $case,
        'exhibits' => $exhibits,
        'preview' => true,
        'photos' => implode(',', $selectedPhotos),
    ]);
})->name('cases.exhibit.preview')->middleware('auth');

Route::get('/admin/cases/{case}/exhibit/pdf', function (CaseModel $case) {
    abort_unless(Auth::user()?->role === 'ADMIN', 403);

    $selectedPhotos = array_values(array_filter(explode(',', (string) request('photos'))));

    return ViewCase::generateExhibitPdf($case, $selectedPhotos);
})->name('cases.exhibit.pdf')->middleware('auth');

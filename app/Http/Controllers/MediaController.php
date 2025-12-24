<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function edit(Media $media)
    {
        return view('photographer.edit', compact('media'));
    }

    public function update(Request $request, Media $media)
    {
        $request->validate([
            'description' => 'required|string|max:255',
        ]);

        $media->update([
            'description' => $request->description,
        ]);

        return redirect()->route('photographer.case.show', $media->case_id)
                         ->with('success', 'Media updated successfully.');
    }

    public function destroy(Media $media)
    {
        // Delete file from storage
        if ($media->path && Storage::exists('public/' . $media->path)) {
            Storage::delete('public/' . $media->path);
        }

        $media->delete();

        return back()->with('success', 'Media deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Designs;

use App\Models\Design;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use App\Jobs\DeleteImage;
use Illuminate\Support\Facades\Storage;

class DesignController extends Controller
{
    public function update(Request $request, $id)
    {
        $design = Design::findOrFail($id);

        $this->authorize('update', $design);

        $this->validate($request, [
            'title' => ['required', 'max:255', 'unique:designs,title,' . $id],
            'description' => ['required', 'min:20', 'max:200', 'string'],
            'is_live' => ['required']
        ]);

        $design->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'is_live' => !$design->upload_successful ? false : $request->is_live
        ]);

        return new DesignResource($design);
    }

    public function destroy(Design $design)
    {
        $this->authorize('delete', $design);

        // Delete images from file system
        // $this->dispatch(new DeleteImage($design->disk, $design->image)); // jobs keeps failing, assumes record is already deleted.
        // Delete the files associated with the record.
        foreach(['thumbnail', 'large', 'original'] as $size) {
            // Check if the file exists on the disk
            if (Storage::disk($design->disk)->exists("uploads/designs/{$size}/". $design->image)) {
                Storage::disk($design->disk)->delete("uploads/designs/{$size}/". $design->image);
            }
        }
        $design->delete();

        return response()->json(['message' => 'Record deleted successfully'], 200);
    }
}

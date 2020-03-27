<?php

namespace App\Http\Controllers\Designs;

use App\Models\Design;
use App\Jobs\DeleteImage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Contracts\DesignInterface;

class DesignController extends Controller
{
    protected $designs;

    public function __construct(DesignInterface $designs)
    {
        $this->designs = $designs;
    }

    public function index ()
    {
        $designs = $this->designs->all();
        return DesignResource::collection($designs);
    }

    public function findDesign($id)
    {
        $design = $this->designs->find($id);
        return new DesignResource($design);
    }

    public function update(Request $request, $id)
    {
        $design = Design::findOrFail($id);

        $this->authorize('update', $design);

        $this->validate($request, [
            'title' => ['required', 'max:255', 'unique:designs,title,' . $id],
            'description' => ['required', 'min:20', 'max:200', 'string'],
            'is_live' => ['required'],
            'tags' => ['required']
        ]);

        $design = $this->designs->update($id, [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'is_live' => !$design->upload_successful ? false : $request->is_live
        ]);

        $this->designs->applyTags($id, $request->tags);

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
        $this->designs->delete();

        return response()->json(['message' => 'Record deleted successfully'], 200);
    }
}

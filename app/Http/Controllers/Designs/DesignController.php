<?php

namespace App\Http\Controllers\Designs;

use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use App\Jobs\DeleteImage;
use App\Models\Design;
use App\Repositories\Contracts\DesignInterface;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Eloquent\Criteria\ForUser;
use App\Repositories\Eloquent\Criteria\IsLive;
use App\Repositories\Eloquent\Criteria\LatestFirst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DesignController extends Controller
{
    protected $designs;

    public function __construct(DesignInterface $designs)
    {
        $this->designs = $designs;
    }

    public function index()
    {
        $designs = $this->designs->withCriteria([
            new LatestFirst,
            new IsLive,
            new ForUser(2),
            new EagerLoad(['user', 'comments', 'tags']),
        ])->all();

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
            'title' => ['required', 'max:255', 'unique:designs,title,'.$id],
            'description' => ['required', 'min:20', 'max:200', 'string'],
            'is_live' => ['required'],
            'tags' => ['required'],
            'team' => ['required_if:assign_to_team,true'],
        ]);

        $design = $this->designs->update($id, [
            'team_id' => $request->team,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'is_live' => ! $design->upload_successful ? false : $request->is_live,
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
        foreach (['thumbnail', 'large', 'original'] as $size) {
            // Check if the file exists on the disk
            if (Storage::disk($design->disk)->exists("uploads/designs/{$size}/".$design->image)) {
                Storage::disk($design->disk)->delete("uploads/designs/{$size}/".$design->image);
            }
        }
        $this->designs->delete($design->id);

        return response()->json(['message' => 'Record deleted successfully'], 200);
    }

    public function like($id)
    {
        $design = $this->designs->like($id);

        return new DesignResource($design);
    }

    public function checkIfUserHasLiked($designId)
    {
        $isLiked = $this->designs->isLikedByUser($designId);

        return response()->json(['liked' => $isLiked], 200);
    }
}

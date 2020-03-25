<?php

namespace App\Http\Controllers\Designs;

use App\Models\Design;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DesignController extends Controller
{
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => ['required', 'max:255', 'unique:designs,title,' . $id],
            'description' => ['required', 'min:20', 'max:200', 'string'],
            'is_live' => ['required']
        ]);

        $design = Design::find($id);

        $design->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'is_live' => !$design->upload_successful ? false : $request->is_live
        ]);

        return response()->json($design, 200);
    }
}

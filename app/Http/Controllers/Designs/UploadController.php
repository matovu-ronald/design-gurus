<?php

namespace App\Http\Controllers\Designs;

use App\Jobs\UploadImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $this->validate($request, [
            'image' => ['required', 'mimes:jpeg,gif,bmp,png,jpg', 'max:2048']
        ]);

        // Get the image
        $image = $request->file('image');
        $image_path = $image->getPathname();

        // Get the original file name and replace spaces with underscore
        // Business Cards.png = timestamp()_business_card.png
        $filename = time() . "_" . preg_replace('/\s+/', '_', strtolower($image->getClientOriginalName()));

        // Move the image to the temporary location (temp)
        $tmp = $image->storeAs('uploads/original', $filename, 'tmp');

        // Create the database record for the design
        $design = auth()->user()->designs()->create([
            'image' => $filename,
            'disk' => config('site.upload_disk')
        ]);

        // Dispatch the job to handle image manipulation.
        $this->dispatch(new UploadImage($design));

        return response()->json($design, 200);
    }
}

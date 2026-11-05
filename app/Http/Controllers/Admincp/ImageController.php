<?php

namespace App\Http\Controllers\Admincp;

use App\Http\Controllers\Controller;
use App\Models\Admincp\Image;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\Facades\Image as InterventionImage;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        return view('admincp.images.index')->with([
            'images' => Image::orderBy('created_at', 'desc')->paginate(24),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        // Resize, encode the image
        $imageName = explode('.', $request->file('image')->getClientOriginalName());

        $image = InterventionImage::make($request->file('image'))
            // ->resize(740, null, function ($constraint) {
            //     $constraint->aspectRatio();
            // })
            ->encode('png', 100);

        // Generate a path for the image
        // $imageName = $image->getClientOriginalName();
        Storage::put('public/images/' . $imageName[0] . '.png', $image);
        $imagePath = Storage::url('public/images/' . $imageName[0] . '.png');
        if (Image::where('path', $imagePath)->exists()) {
            return redirect()->route('admincp.images.index')->with([
                'error' => 'Image name already exists!',
            ]);
        } else {
            Image::create([
                'path' => $imagePath,
            ]);

            return redirect()->route('admincp.images.index')->with([
                'success' => 'Image has been added!',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Image $image
     * @return RedirectResponse
     */
    public function destroy(Image $image)
    {
        // Delete the image from public storage
        Storage::disk('public')->delete(strstr($image->path, '/images'));

        $image->delete();

        return redirect()->route('admincp.images.index')->with([
            'success' => 'Image has been deleted!',
        ]);
    }
}

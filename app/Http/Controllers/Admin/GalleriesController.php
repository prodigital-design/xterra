<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GalleryRequest;
use App\Media\Gallery;
use App\Rules\AtLeastOneTranslation;
use Illuminate\Http\Request;

class GalleriesController extends Controller
{
    public function store(GalleryRequest $request)
    {
        Gallery::create($request->galleryInfo()->toArray());
    }

    public function update(Gallery $gallery, GalleryRequest $request)
    {
        $gallery->update($request->galleryInfo()->toArray());
    }

    public function delete(Gallery $gallery)
    {
        $gallery->delete();
    }
}
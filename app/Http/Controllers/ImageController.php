<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    //


    public function reactions(Image $image)
    {
        dd($image->with('reactions')->get());
        return $image->reactions();
    }
}

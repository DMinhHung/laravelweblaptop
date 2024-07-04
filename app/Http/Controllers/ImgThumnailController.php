<?php

namespace App\Http\Controllers;

use App\Models\ImgThumnail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ImgThumnailController extends Controller
{
    public function index()
    {
        $imgThumnail = ImgThumnail::get();
        return response()->json($imgThumnail);
    }
    
    public function store(Request $request)
    {
        Log::info($request->all());
        $request->validate([
            'imgThumnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'imgReviews' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($file = $request->file('imgThumnail')) {
            $imageName = uniqid() . '_' . $file->getClientOriginalName();
            $file->move(public_path('imgThumnail'), $imageName);
            $imagePath = $imageName;
        }
        if ($file = $request->file('imgReviews')) {
            $imageName = uniqid() . '_' . $file->getClientOriginalName();
            $file->move(public_path('imgReviews'), $imageName);
            $imagePath = $imageName;
        }
        $imageJson = json_encode($imagePath);
        $imgThumnail = ImgThumnail::create([
            'imgThumnail' => $imageJson,
            'imgReviews' => $imageJson,
        ]);
        $imgThumnail->save();
        return response()->json($imgThumnail, 201);
      
    }
}

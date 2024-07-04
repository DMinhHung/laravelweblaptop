<?php

namespace App\Http\Controllers;

use App\Models\ShoppingCart;
use App\Models\Staffs;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StaffsController extends Controller
{
    public function index()
    {
        $staffs = Staffs::get();

        return response()->json($staffs);
    }

    public function store(Request $request)
    {
        Log::info($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|string|max:10',
            'position' => 'required|string|max:255',
        ]);

        $imagePath = null;

        if ($file = $request->file('img')) {
            $imageName = uniqid() . '_' . $file->getClientOriginalName();
            $file->move(public_path('staffs'), $imageName);
            $imagePath = $imageName;
        }

        $imageJson = json_encode($imagePath);


        $staffs = Staffs::create([
            'name' => $request->name,
            'img' => $imageJson,
            'gender' => $request->gender,
            'position' => $request->position,
        ]);
        $staffs->save();
        return response()->json($staffs, 201);
    }

    public function show($id)
    {
        $staff = Staffs::findOrFail($id);
        return response()->json($staff);
    }

    public function update(Request $request, $id)
    {
        Log::info($request->all());
        $staff = Staffs::findOrFail($id);
    
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('staffs'), $filename);
            $staff->img = json_encode($filename);
        } else {
            $staff->img = $request->input('existing_img');
        }
        
        $staff->name = $request->name;
        $staff->gender = $request->gender;
        $staff->position = $request->position;
        $staff->save();
    
        return response()->json($staff, 200);
    }

    public function destroy($id)
    {
        $staff = Staffs::findOrFail($id);
        $staff->delete();
        return response()->json(null, 204);
    }

    public function search(Request $request)
    {
        $term = $request->query('search');

        $staffs = Staffs::where('name', 'like', '%' . $term . '%')->get();

        return response()->json($staffs);
    }
}

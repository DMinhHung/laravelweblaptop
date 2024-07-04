<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\ShoppingCart;
use App\Models\Staffs;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customers::get();

        return response()->json($customers);
    }

    public function store(Request $request)
    {
        $imagePath = null;
        $genderr = $request->gender;
        Log:info($genderr);
        if ($file = $request->file('img')) {
            $imageName = uniqid() . '_' . $file->getClientOriginalName();
            $file->move(public_path('customer'), $imageName);
            $imagePath = $imageName;
        }

        $imageJson = json_encode($imagePath);

        $customers = Customers::create([
            'name' => $request->name,
            'img' => $imageJson,
            'gender' => $genderr,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);
        $customers->save();
        return response()->json($customers, 201);
    }

    public function show($id)
    {
        $customers = Customers::findOrFail($id);
        return response()->json($customers);
    }

    public function update(Request $request, $id)
    {
        $customers = Customers::findOrFail($id);

        $imagePath = null;

        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('customer'), $filename);
            $customers->img = json_encode($filename);
        } else {
            $customers->img = $request->input('existing_img');
        }

   
        $customers->name = $request->name;
        $customers->gender = $request->gender;
        $customers->email = $request->email;
        $customers->phone = $request->phone;
        $customers->address = $request->address;
        $customers->save();
        return response()->json($customers, 200);
    }

    public function destroy($id)
    {
        $customers = Customers::findOrFail($id);
        $customers->delete();
        return response()->json(null, 204);
    }

    public function search(Request $request)
    {
        $term = $request->query('search');

        $customer = Customers::where('name', 'like', '%' . $term . '%')->get();

        return response()->json($customer);
    }
}

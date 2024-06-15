<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product_detail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductAdminController extends Controller
{
    // Hiển thị danh sách sản phẩm dưới dạng API
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function getcategories()
    {
        $categories = Categories::all();
        return response()->json($categories);
    }

    // Lưu sản phẩm mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        Log::info($request->all());
        $request->validate([
            'name' => 'string|max:255',
            'price' => 'numeric',
            'quantity' => 'numeric',
            // 'img' => 'nullable|image',
            'description' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        $images = [];
        foreach ($request->file('img') as $file) {
            $imageName = uniqid() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $imageName);
            $images[] = 'images/' . $imageName;
        }
        $imageJson = json_encode($images);
        Log::info("IMG" . ($imageJson));
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'img' => $imageJson,
            'description' => $request->description,
            'color' => $request->color,
            'categories_id' => $request->categories_id,
        ]);
        $product->save();
        $product_detail = Product_detail::create([
            'product_id' => $product->id,
            'CPU' => $request->CPU,
            'RAM' => $request->RAM,
            'HARDWARE' => $request->HARDWARE,
            'CARD' => $request->CARD,
            'MONITOR' => $request->MONITOR,
            'PIN' => $request->PIN,
            'WEIGHT' => $request->WEIGHT,
            'MATERRIAL' => $request->MATERRIAL,
            'LENGHT' => $request->LENGHT,
        ]);
        $product_detail->save();
        return response()->json($product, 201);
    }

    public function show($id)
    {
        $product = Product::with('details')->findOrFail($id);
        return response()->json($product);
    }
    public function search(Request $request)
    {
        $term = $request->query('search');

        $products = Product::where('name', 'like', '%' . $term . '%')->get();

        return response()->json($products);
    }
    public function update(Request $request, $id)
    {
        $product = Product::with('details')->findOrFail($id);
        // $currentImages = json_decode($product->img, true) ?? [];
        $newImages = [];
        foreach ($request->img as $key => $img) {
            if ($img instanceof \Illuminate\Http\UploadedFile) {
                $imageName = uniqid() . '_' . $img->getClientOriginalName();
                $img->move(public_path('images'), $imageName);
                $newImagePath = 'images/' . $imageName;
                $newImages[$key] = $newImagePath;
                Log::info('Đã lưu ảnh mới: ' . $newImagePath);
            } else {
                $newImages[$key] = $img;
            }
        }
        ksort($newImages);
        $product->img = json_encode($newImages);
        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'color' => $request->color,
            'quantity' => $request->quantity,
            'categories_id' => $request->categories_id,
        ]);

        $product_detail = Product_detail::where('product_id', $id)->first();
        $product_detail->update([
            'CPU' => $request->CPU,
            'RAM' => $request->RAM,
            'HARDWARE' => $request->HARDWARE,
            'CARD' => $request->CARD,
            'MONITOR' => $request->MONITOR,
            'PIN' => $request->PIN,
            'WEIGHT' => $request->WEIGHT,
            'MATERRIAL' => $request->MATERRIAL,
            'LENGHT' => $request->LENGHT,
        ]);

        return response()->json($product);
    }
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(null, 204);
    }
}

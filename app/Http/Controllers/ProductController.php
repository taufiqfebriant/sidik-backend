<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
  public function index(Request $request)
  {
    $page = $request->query('page', 1);
    $perPage = $request->query('per_page', 10);

    $products = Product::paginate($perPage, ['*'], 'page', $page);

    $products->getCollection()->transform(function ($product) {
      return [
        'id' => $product->id,
        'name' => $product->name,
        'description' => $product->description,
        'price' => $product->price,
        'image' => $product->image ? asset(Storage::url($product->image)) : null,
        'created_at' => $product->created_at,
        'updated_at' => $product->updated_at,
      ];
    });

    return response()->json([
      'message' => 'Products retrieved successfully',
      'data' => $products,
    ]);
  }

  public function store(Request $request)
  {
    if (auth()->user()->role_id !== 1) {
      return response()->json(['message' => 'Unauthorized'], 403);
    }

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'price' => 'required|numeric|min:0',
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $imagePath = null;
    if (!empty($validated['image'])) {
      $imagePath = $validated['image']->store('products', 'public');
    }

    $product = Product::create([
      'name' => $validated['name'],
      'description' => empty($validated['description']) ? null : $validated['description'],
      'price' => $validated['price'],
      'image' => $imagePath,
    ]);

    return response()->json([
      'message' => 'Product created successfully',
      'data' => $product,
    ], 201);
  }

  public function show(Product $product)
  {
    $productData = [
      'id' => $product->id,
      'name' => $product->name,
      'description' => $product->description,
      'price' => $product->price,
      'image' => $product->image ? asset(Storage::url($product->image)) : null,
      'created_at' => $product->created_at,
      'updated_at' => $product->updated_at,
    ];

    return response()->json([
      'message' => 'Product retrieved successfully',
      'data' => $productData,
    ]);
  }

  public function update(Request $request, Product $product)
  {
    if (auth()->user()->role_id !== 1) {
      return response()->json(['message' => 'Unauthorized'], 403);
    }

    $validated = $request->validate([
      'name' => 'sometimes|required|string|max:255',
      'description' => 'nullable|string',
      'price' => 'sometimes|required|numeric|min:0',
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $imagePath = $product->image;
    if (!empty($validated['image'])) {
      if ($product->image) {
        Storage::disk('public')->delete($product->image);
      }

      $imagePath = $validated['image']->store('products', 'public');
    }

    $product->update([
      'name' => $validated['name'] ?? $product->name,
      'description' => array_key_exists('description', $validated)
        ? (empty($validated['description']) ? null : $validated['description'])
        : $product->description,
      'price' => $validated['price'] ?? $product->price,
      'image' => $imagePath,
    ]);

    return response()->json([
      'message' => 'Product updated successfully',
      'data' => $product,
    ]);
  }

  public function destroy(Product $product)
  {
    if (auth()->user()->role_id !== 1) {
      return response()->json(['message' => 'Unauthorized'], 403);
    }

    if ($product->image) {
      Storage::disk('public')->delete($product->image);
    }

    $product->delete();

    return response()->json([
      'message' => 'Product deleted successfully',
    ]);
  }
}

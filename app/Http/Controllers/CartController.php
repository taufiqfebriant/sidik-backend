<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CartController extends Controller
{
  public function index()
  {
    $user = Auth::user();

    $cartItems = Cart::where('user_id', $user->id)
      ->with('product')
      ->get();

    $cartItems->transform(function ($cartItem) {
      return [
        'id' => $cartItem->id,
        'user_id' => $cartItem->user_id,
        'product_id' => $cartItem->product_id,
        'quantity' => $cartItem->quantity,
        'product' => $cartItem->product ? [
          'id' => $cartItem->product->id,
          'name' => $cartItem->product->name,
          'description' => $cartItem->product->description,
          'price' => $cartItem->product->price,
          'image' => $cartItem->product->image ? asset(Storage::url($cartItem->product->image)) : null,
          'created_at' => $cartItem->product->created_at,
          'updated_at' => $cartItem->product->updated_at,
        ] : null,
        'created_at' => $cartItem->created_at,
        'updated_at' => $cartItem->updated_at,
      ];
    });

    return response()->json([
      'message' => 'Cart items retrieved successfully',
      'data' => $cartItems,
    ]);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'product_id' => 'required|exists:products,id',
      'quantity' => 'required|integer|min:1',
    ]);

    $user = Auth::user();

    $cartItem = Cart::where('user_id', $user->id)
      ->where('product_id', $validated['product_id'])
      ->first();

    if ($cartItem) {
      $cartItem->quantity += $validated['quantity'];
      $cartItem->save();
    } else {
      Cart::create([
        'user_id' => $user->id,
        'product_id' => $validated['product_id'],
        'quantity' => $validated['quantity'],
      ]);
    }

    return response()->json([
      'message' => 'Product added to cart successfully',
    ], 201);
  }

  public function update(Request $request, Cart $cart)
  {
    $validated = $request->validate([
      'quantity' => 'required|integer|min:1',
    ]);

    $cart->update([
      'quantity' => $validated['quantity'],
    ]);

    return response()->json([
      'message' => 'Cart item updated successfully',
    ]);
  }

  public function destroy(Cart $cart)
  {
    $cart->delete();

    return response()->json([
      'message' => 'Cart item removed successfully',
    ]);
  }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
  public function store(Request $request)
  {
    $user = Auth::user();

    $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

    if ($cartItems->isEmpty()) {
      return response()->json(['message' => 'Your cart is empty'], 400);
    }

    DB::beginTransaction();

    try {
      $totalPrice = $cartItems->sum(fn($cart) => $cart->product->price * $cart->quantity);

      $order = Order::create([
        'user_id' => $user->id,
        'status' => 'pending',
        'total_price' => $totalPrice,
      ]);

      foreach ($cartItems as $cart) {
        OrderItem::create([
          'order_id' => $order->id,
          'product_id' => $cart->product_id,
          'quantity' => $cart->quantity,
          'price' => $cart->product->price,
        ]);
      }

      Cart::where('user_id', $user->id)->delete();

      DB::commit();

      return response()->json([
        'message' => 'Order placed successfully',
        'data' => $order->load('items'),
      ], 201);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json(['message' => 'Failed to place order'], 500);
    }
  }
}

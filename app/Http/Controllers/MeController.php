<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MeController extends Controller
{
  public function index(Request $request)
  {
    return response()->json([
      'data' => $request->user(),
    ]);
  }
}

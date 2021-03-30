<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Product;
use App\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){

      $products=Category::with('Products')->get();

      return response()->json($products);
    }
}

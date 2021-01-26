<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\Product as ProductResource;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $product = Product::create($request->all());

        return response()->json(new ProductResource($product), 201);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        return response()->json(new ProductResource($product));
    }
}

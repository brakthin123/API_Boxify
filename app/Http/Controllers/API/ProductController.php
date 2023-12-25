<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function storeProduct(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'        => 'required|string|max:255',
                'image'       => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'quantity'    => 'required|integer|min:1',
                'price'       => 'required|numeric|min:0',
                'category'    => 'required|string|max:255',
                'description' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Validation error',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            // Assuming you have a Product model
            $product = new Product();
            $product->name        = $request->input('name');
            $product->image_path  = $request->file('image')->store('image', 'public');
            $product->quantity    = $request->input('quantity');
            $product->price       = $request->input('price');
            $product->category    = $request->input('category');
            $product->description = $request->input('description');
            $product->save();

            return response()->json([
                'status'  => 'success',
                'message' => 'Product created successfully',
                'data'    => $product,
            ], 201);

        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }
}

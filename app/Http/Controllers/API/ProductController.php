<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

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

            $imagePath = $request->file('image')->store('image', 'public/upload/products/');


            $product = Product::create([
                'name'        => $request->input('name'),
                'image'       => $imagePath,
                'quantity'    => $request->input('quantity'),
                'price'       => $request->input('price'),
                'category'    => $request->input('category'),
                'description' => $request->input('description'),
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Product created successfully',
                'data'    => $product,
            ], 201);
        } catch (Exception $e) {
            // Log the exception for debugging
            Log::error('Error storing product: ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            return response()->json(['errors' => 'Internal Server Error'], 500);
        }
    }
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json(['data' => $product->toArray()]);
    }
}

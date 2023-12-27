<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class FolderController extends Controller
{
        public function store(Request $request)
        {
            $data = $request->validate([
                'name' => 'required|string',
                'image' => 'required|string',
                'price' => 'required|numeric',
                'unit' => 'required|string',
                'description' => 'required|string',
            ]);
    
            $folder = Folder::create($data);
    
            return response()->json(['message' => 'Product created successfully', 'product' => $folder]);
        }
    
}
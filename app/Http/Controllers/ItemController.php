<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'image' => 'required|string',
            'price' => 'required|numeric',
            'unit' => 'required|string',
            'description' => 'required|string',
            'quantity' => 'required|numeric',
            'folder_id' => 'required|exists:folders,id',
        ]);

        $item = Item::create($data);

        return response()->json([
            'status' => 'true',
            'message' => 'Item created successfully',
            'data' => $item]);
    }
    public function index(Request $request, $folder_id)
    {
        // Retrieve items based on the provided folder_id
        $items = Item::where('folder_id', $folder_id)->get();

        if ($items->isEmpty()) {
            return response()->json(['message' => 'No items found for the given folder_id', 'items' => []]);
        }
        
        return response()->json(['message' => 'Items retrieved successfully', 'items' => $items]);
    }
}

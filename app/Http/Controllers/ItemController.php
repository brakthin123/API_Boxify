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
        'image' => 'string',
        'price' => 'numeric',
        'unit' => 'string',
        'description' => 'string',
        'quantity' => 'numeric',
        'folder_id' => 'required|exists:folders,id',
    ]);

    // Decode base64 image string
    $imageData = base64_decode($data['image']);

    // Generate a unique filename for the image
    $filename = 'image_' . time() . '.png'; // You can adjust the filename and extension as needed

    // Save the image to the storage path
    $path = storage_path('app/public/images/') . $filename;
    file_put_contents($path, $imageData);

    // Add the image path to the data array
    $data['image_path'] = 'images/' . $filename; // Adjust the path based on your storage configuration

    // Create the item
    $item = Item::create($data);

    return response()->json([
        'status' => 'true',
        'message' => 'Item created successfully',
        'data' => $item
    ]);
}

    public function index(Request $request, $folder_id)
    {
        // Retrieve items based on the provided folder_id
        $items = Item::where('folder_id', $folder_id)->get();

        if ($items->isEmpty()) {
            return response()->json(['message' => 'No items found for the given folder_id', 'items' => []]);
        }

        return response()->json([
            'status' => 'true',
            'message' => 'Items retrieved successfully',
            'data' => $items]);
    }
}

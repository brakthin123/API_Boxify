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
    
        // Decode base64 image string
        $imageData = base64_decode($data['image']);
    
        // Generate a unique filename for the image
        $filename = 'image_' . time() . '.png'; // You can adjust the filename and extension as needed
    
        // Save the image to the storage path
        $path = storage_path('app/public/images/') . $filename;
        file_put_contents($path, $imageData);
    
        // Add the image path to the data array
        $data['image_path'] = 'images/' . $filename; // Adjust the path based on your storage configuration
    
        // Authenticate the user and create the folder with additional image information
        $user = JWTAuth::parseToken()->authenticate();
        $folder = $user->folders()->create($data);
    
        return response()->json([
            'status' => 'true',
            'message' => 'Folder created successfully',
            'data' => $folder
        ]);
    }
    

    public function index(Request $request)
    {
        // Get the authenticated user
        $user = JWTAuth::parseToken()->authenticate();

        // Retrieve all folders associated with the user
        $query = $user->folders();

        // Check if a search query parameter is provided
        if ($request->has('search')) {
            $searchTerm = $request->input('search');

            // Add a where clause to filter folders by name
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        // Get the filtered folders
        $folders = $query->get();

        // Check if folders exist
        if ($folders->isEmpty()) {
            return response()->json(['message' => 'No matching folders found for the user'], 404);
        }

        return response()->json(['message' => 'Folders retrieved successfully', 'folders' => $folders]);
    }
}

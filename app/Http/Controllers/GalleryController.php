<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        return GalleryItem::all();
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'description' => 'nullable|string',
        'category' => 'required|string|in:events,training,community',  // Add validation for category
    ]);

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $originalName = $file->getClientOriginalName(); // Get the original file name
        $path = $file->storeAs('img', $originalName, 'public'); // Store in the 'img' folder
        $validatedData['image'] = $path; // Store the relative path
    }

    $galleryItem = GalleryItem::create($validatedData);
    return response()->json($galleryItem, 201);
}

    public function show($id)
    {
        return GalleryItem::findOrFail($id);
    }

    public function update(Request $request, $id)
{
    // Validate incoming request data
    $request->validate([
        'title' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'description' => 'nullable|string',
        'category' => 'required|string|in:events,training,community',  // Ensure category is included
    ]);

    // Find the gallery item by ID
    $item = GalleryItem::findOrFail($id);

    // Update the fields
    $item->title = $request->input('title');
    $item->description = $request->input('description');
    $item->category = $request->input('category');  // Update category

    // Check if a new image is uploaded
    if ($request->hasFile('image')) {
        // Delete the old image if it exists
        if ($item->image) {
            \Storage::disk('public')->delete($item->image);
        }

        // Store the new image
        $file = $request->file('image');
        $path = $file->storeAs('img', $file->getClientOriginalName(), 'public');
        $item->image = $path; // Update image path
    }

    // Save the updated gallery item
    $item->save();

    return response()->json($item);
}


    public function destroy($id)
    {
        GalleryItem::destroy($id);
        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    // Modification of the typing Request -> CategoryRequest
    public function store(CategoryRequest $request)
    {
        // Fetch the data in the CategoryRequest file and validate the data
        $validated = $request->validated();

        if(!isset($validated['slug']) || $validated['slug'] === ""){
            $validated['slug'] = Str::slug($validated['name']);
        }

        $newCategory = Category::create($validated);

        return new CategoryResource($newCategory, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    // Modification of the typing Request -> CategoryRequest
    public function update(CategoryRequest $request, Category $category)
    {
        // Validation of the data stored in the variable $validated
        $validated = $request->validated();

        if(!isset($validated['slug']) || $validated['slug'] === ""){
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Updated the data
        $category->update($validated);
        // Return the updated data in JSON format
        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Check if the category exists
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Perform the deletion
        try {
            $category->delete();
            return response()->json(['message' => 'Category successfully deleted'], 200);
        } catch (\Exception $e) {
            // Handle exception if delete fails
            return response()->json(['message' => 'Failed to delete the category', 'error' => $e->getMessage()], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Gig;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\TagRequest;
use App\Http\Resources\TagResource;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allTags = Tag::all();
        return TagResource::collection($allTags);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TagRequest $request)
    {
        // Creates a new tag using the validated data from the request
        $newTag = Tag::create($request->validated());

        // Return the new tag data in JSON format
        return new TagResource($newTag, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        // Use findOrFail to search for the gig by ID and return a 404 response if not found
        $tag = Tag::findOrFail($tag->id);

        // Return the tag data in JSON format
        return new TagResource($tag);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TagRequest $request, Tag $tag)
    {
        // Validate query data
        $validatedData = $request->validated();

        // Check if the tag exists
        if (!$tag) {
            return response()->json(['error' => 'tag not found'], 404);
        }

        // Attempt to update the tag data with validated data
        try {
            $tag->update($validatedData);
        } catch (\Exception $e) {
            // If there is an error, return a JSON response with an error message
            return response()->json(['error' => 'Tag update failed'], 500);
        }

        // Return the updated data in JSON format
        return new TagResource($tag);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        // Check if the tag exists
        if (!$tag) {
            return response()->json(['message' => 'Tag not found'], 404);
        }

        // Perform the deletion
        try {
            $tag->delete();
            return response()->json(['message' => 'Tag successfully deleted'], 200);
        } catch (\Exception $e) {
            // Handle exception if delete fails
            return response()->json(['message' => 'Failed to delete the tag', 'error' => $e->getMessage()], 500);
        }
    }

    // Method to obtain the list of tags associated with a gig
    public function getTagsByGig($id)
    {
        // Fetch gig by id
        $gig = Gig::findOrFail($id);

        // Fetch tags associated with the gig
        $tags = $gig->load('tags');

        // Return tags in JSON format with key 'tags_by_gig'
        return TagResource::collection($tags, 200);
    }
}

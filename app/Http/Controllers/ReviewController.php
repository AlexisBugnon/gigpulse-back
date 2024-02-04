<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Gig;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allReviews = Review::all();
        return ReviewResource::collection($allReviews);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ReviewRequest $request)
    {
        // Create a new review using the validated data from the request
        $newReview = Review::create($request->validated());

        // Fetch the associated gig
        $gig = Gig::findOrFail($request->gig_id);

        // Calculation of the new average rating for this gig
        // We will search for the reviews associated with the service with the id, and we calculate the average rating
        $averageRating = Review::where('gig_id', $gig->id)->avg('rating');

        // Update the gig with the new average rating
        $gig->average_rating = $averageRating;
        $gig->save();

        // Return the new review data in JSON format
        return new ReviewResource($newReview, 201); // 201 means "Created"
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        // Use findOrFail to search for the review by ID and return a 404 response if not found
        $review = Review::findOrFail($review->id);

        // Return the review data in JSON format
        return new ReviewResource($review);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReviewRequest $request, Review $review)
    {
        // Validate query data
        $validatedData = $request->validated();

        // Check if the gig exists
        if (!$review) {
            return response()->json(['error' => 'Review not found'], 404);
        }

        // Attempt to update the review data with the validated data
        try {
            $review->update($validatedData);
        } catch (\Exception $e) {
            // If there is an error, return a JSON response with an error message
            return response()->json(['error' => 'Review update failed'], 500);
        }

        // Return the updated data in JSON format
        return new ReviewResource($review);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        // Check if the review exists
        if (!$review) {
            return response()->json(['message' => 'review not found'], 404);
        }

        // Perform the deletion
        try {
            $review->delete();

            // Fetch the associated gig
            $gig = Gig::findOrFail($review->gig_id);

            // Calculation of the new average rating for this gig
            // We will search for the reviews associated with the service with the id, and we calculate the average rating
            $averageRating = Review::where('gig_id', $gig->id)->avg('rating');

            // Update the gig with the new average rating
            $gig->average_rating = $averageRating;
            $gig->save();

            return response()->json(['message' => 'review successfully deleted'], 200);
        } catch (\Exception $e) {
            // Handle exception if delete fails
            return response()->json(['message' => 'Failed to delete the tag', 'error' => $e->getMessage()], 500);
        }
    }

    // Method to obtain the list of reviews associated with a gig specified by its identifier
    public function getReviewsByGig($id)
    {
        // Fetch reviews associated with the gig
        $reviews = Review::where("gig_id", $id)->get();

        // Return reviews in JSON format with key 'reviews_by_gig'
        return ReviewResource::collection($reviews, 200);
    }
}

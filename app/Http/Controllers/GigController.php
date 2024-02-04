<?php

namespace App\Http\Controllers;

use App\Http\Requests\GigRequest;
use App\Http\Resources\GigResource;
use App\Models\Gig;
use App\Models\Category;
use Illuminate\Auth\Events\Validated;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch the list of all gigs
        // $allGigs = Gig::all() before pagination
        $allGigs = Gig::paginate(9);

        // Return the list of gigs in JSON format
        return GigResource::collection($allGigs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GigRequest $request)
    {
        // Validate query data
        $validatedData = $request->validated();
        // Create a slug based on the title of the created gig
        $slug = Str::slug($validatedData['title']);
        // Create a new gig using the validated data from the request
        $newGig = Gig::create($validatedData);
        // This line allows you to create a relationship (and therefore lines in the gig_user pivot table)
        // between the new gig and the tags entered in the request
        $newGig->tags()->attach($validatedData['tags']);
        $newGig->slug = $slug;
        $newGig->save();
        // Return the new gig data in JSON format
        return new GigResource($newGig, 201); // 201 means "Created"
    }

    /**
     * Display the specified resource.
     */
    // Laravel makes the association of the gig concerned in the url
    public function show(Gig $gig)
    {
        // Use findOrFail to search for the gig by ID and return a 404 response if not found
        // The with tag will display the associated tags
        $gig = Gig::with('tags')->find($gig->id);

        // Return the gig data in JSON format
        return new GigResource($gig);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gig $gig)
    {

        // Get the ID of the currently authenticated user and compare with the $user variable containing the user object via id
        if (Auth::id() != $gig->user_id && Auth::user()->role != "Super admin" && Auth::user()->role != "Admin") {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // si l'utilisateur tente de modifier son propre profil, il peut tout modifier sauf son role ...
        if (Auth::id() == $gig->user_id) {
            $validatedData = $request->validate([
                'title' => 'max:255',
                'picture' => 'url',
                'description' => 'string',
                'price' => 'numeric',
                'category_id' => 'exists:categories,id',
                'is_active' => 'boolean',
                'tags' => 'array'
            ]);
            if(isset($validatedData['tags'])){
                $gig->tags()->detach();
                $gig->tags()->attach($validatedData['tags']);
                $gig->save();
            }
        }

        // si l'utilisateur est super admin mais qu'il ne modifie pas sont propre profil il peut desactiver le profil et changer le rol d'autres utilisateurs
        if ((Auth::user()->role == "Super admin" || Auth::user()->role == "Admin") && Auth::id() != $gig->user_id) {
            $validatedData = $request->validate([
                'is_active' => 'boolean',
            ]);
        }

        try {
            $gig->update($validatedData);
        } catch (\Exception $e) {
            // If there is an error, return a JSON response with an error message
            return response()->json(['error' => 'User update failed'], 500);
        }

        // Return the updated data in JSON format
        return new GigResource($gig);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gig $gig)
    {
        // Check if the gig exists
        if (!$gig) {
            return response()->json(['message' => 'Gig not found'], 404);
        }

        // We compare the role of the user with user and the id of the connected user with the user_id of gig
        if (Auth::user()->role === "User" && Auth::id() != $gig->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Perform the deletion
        try {
            $gig->delete();
            return response()->json(['message' => 'Gig successfully deleted'], 200);
        } catch (\Exception $e) {
            // Handle exception if delete fails
            return response()->json(['message' => 'Failed to delete the gig', 'error' => $e->getMessage()], 500);
        }
    }

    // Method to display the last 3 gigs (features) on the home page
    public function featuredGigs()
    {
        // Recover the last 3 gigs created
        $featuredGigs = Gig::with('tags')->latest()->take(3)->get();

        // Return gigs in JSON format
        return GigResource::collection($featuredGigs, 200);
    }

    // Method to get the list of gigs by category specified by its identifier
    public function getGigsByCategory(Request $request, $id)
    {
        // Carries out sorting and filtering
        // Validates query data
        $validatedData = $request->validate([
            'filter' => 'array',
            'sort' => 'string|in:average_rating,price,created_at',
            'order' => 'string|in:asc,desc'
        ]);

        // Is sort informed? If yes takes the specified value otherwise the default value 'created_at'
        $sort = $request->input('sort') ? $validatedData['sort'] : 'created_at';
        // Is filter informed? If yes takes the specified value otherwise the default value 'null'
        $filter = $request->input('filter') ? $validatedData['filter'] : null;
        // Is order informed? If yes takes the specified value otherwise the default value 'desc'
        $order = $request->input('order') ? $validatedData['order'] : 'desc';

        // Initialization of the $query which can be used with or without the filter
        $query = Gig::query()->with('tags')->where('category_id', $id)->where('is_active', true);

        // If id tags are informed, then we filter the gigs in relation to the ids of the $filter table
        // the filter returns the gigs which contain at least one of the tags received in the table
        // for example, if in the $filter table only the tag with id 1 is entered,
        // then the filter will return the gigs having at least the id tag 1
        if ($filter) {
            // here returns the gigs having at least the tags present in the $filter table
            $query->whereHas('tags', function ($query) use ($filter) {
                $query->whereIn('tag_id', $filter);
                // ;
            }, '=', count($filter));

            // here we do not return the gigs which have differends tags from the $filter table
            // $query->whereDoesntHave('tags', function ($query) use ($filter) {
            //     $query->whereNotIn('tag_id', $filter);
            // });
        }


        // Whether we filter or not, we sort according to the values ​​received in the request or the default values
        $gigsByCategory = $query->orderBy($sort, $order)->paginate(9);

        return GigResource::collection($gigsByCategory, 200);
    }

    // Method to obtain the list of gigs created by an User
    public function getGigsByUser($id)
    {
        // Fetch gigs based on a specified user
        $gigsByUser = Gig::with('tags')->where('user_id', $id)->paginate(9);

        // Return gigs in JSON format
        return GigResource::collection($gigsByUser, 200);
    }

    // Method for searched gigs
    public function searchedGigs(Request $request)
    {
        // Validate query data
        $validatedData = $request->validate([
            'search' => 'required|string|max:255',
        ]);
        // Fetch all the gig by title or description matching the request data
        $gigs = Gig::where('title', 'LIKE', '%' . $validatedData['search'] . '%')->where('is_active', true)
            ->orwhere('description', 'LIKE', '%' . $validatedData['search'] . '%')->with('tags')->paginate(9);
        return GigResource::collection($gigs, 200);
    }
}

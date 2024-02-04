<?php

namespace App\Http\Controllers;

use App\Models\Gig;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Resources\GigResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allUsers = User::all();
        // Return the result with the display defined in the UserRessource
        return UserResource::collection($allUsers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        // Creates a new user using the validated data from the request
        $newUser = User::create($request->validated());

        // Return the new user data in JSON format
        return new UserResource($newUser, 201); // 201 means "Created"
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Use findOrFail to search for user by ID and return a 404 response if not found
        $user = User::findOrFail($user->id);

        // Return the user data in JSON format
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {

        // Get the ID of the currently authenticated user and compare with the $user variable containing the user object via id
        if (Auth::id() != $user->id && Auth::user()->role != "Super admin" && Auth::user()->role != "Admin") {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        // Check if user exists
        if (!$user) {
            return response()->json(['error' => 'user not found'], 404);
        }

        // si l'utilisateur tente de modifier son propre profil, il peut tout modifier sauf son role ...
        if (Auth::id() == $user->id) {

            $validatedData = $request->validate([
                'name'=> 'string|max:255',
                'email'=> 'email',
                'password' => 'string|min:8',
                'profile_picture'=> 'url',
                'description' => 'string',
                'job' => 'string|max:255',
                'is_active' => 'boolean',
            ]);
        }

        // si l'utilisateur est super admin mais qu'il ne modifie pas sont propre profil il peut desactiver le profil et changer le rol d'autres utilisateurs
        if (Auth::user()->role == "Super admin" && Auth::id() != $user->id) {
            $validatedData = $request->validate([
                'role' => 'in:Super admin,Admin,User',
                'is_active' => 'boolean',
            ]);

        }

        if (Auth::user()->role == "Admin" && Auth::id() != $user->id) {
            $validatedData = $request->validate([
                'is_active' => 'boolean',
            ]);

        }

        try {
            $user->update($validatedData);
        } catch (\Exception $e) {
            // If there is an error, return a JSON response with an error message
            return response()->json(['error' => 'User update failed'], 500);
        }

        // Return the updated data in JSON format
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)

    {
        // Check if user exists
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (Auth::user()->role != "Admin" || Auth::user()->role != "Super admin") {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        // Perform the deletion
        try {
            $user->delete();
            return response()->json(['message' => 'User successfully deleted'], 200);
        } catch (\Exception $e) {
            // Handle exception if delete fails
            return response()->json(['message' => 'Failed to delete the user', 'error' => $e->getMessage()], 500);
        }
    }

    // Method to display the favorites gigs relative to a user
    public function getFavorites(Request $request, $id)
    {
        // Check if the authenticated user is the one requesting their favorites
        if (Auth::id() != $id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        // Fetch user by id
        $user = User::findOrFail($id);

        // Fetch favorite gigs associated with the user
        $userHasFavoriteGigs = $user->load('favoriteGigs');

        // Return the favorite gigs in JSON format
        return new UserResource($userHasFavoriteGigs, 200);
    }

    // Method to get a user by gig
    public function getUserByGig($id)
    {
        // Fetch gig by id
        $gig = Gig::findOrFail($id);

        // Fetch the user associated with the gig
        // possible because in the Gig model, we defined a belongs to relationship in the function user()
        // Laravel uses this relationship to retrieve the user associated with this gig by looking at the foreign key (user_id) in the gigs table and the primary key (id) in the users table.
        $userByGig = $gig->user;

        // Check if the gig exists
        if (!$gig) {
            return response()->json(['error' => 'Gig not found'], 404);
        }

        // Return the user in JSON format
        return new UserResource($userByGig, 200);
    }
    public function addOrDeleteFavorites(Request $request, $userId, $gigId)
    {

        if (Auth::id() != $userId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($userId);
        $gig = Gig::findOrFail($gigId);

        if ($user->favoriteGigs()->find($gig->id)) {
            // Deleting to favorites
            $user->favoriteGigs()->detach($gig->id);
            $message = 'supprimé des favoris avec succès';
        } else {
            // Adding to favorites
            $user->favoriteGigs()->attach($gig->id);
            $message = 'Ajouté aux favoris avec succès';
        }
        return response()->json(['message' => $message, 'gigsFavorites' => $user->favoriteGigs()->pluck('gig_id')], 200);
    }
}

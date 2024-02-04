<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        // Validation of the provided data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // $validatedData stores data validation in an array
        // A new user is created in the database using the User model
        $newUser = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);
        return response()->json(['message' => 'User successfully registered'], 201); // HTTP code 201 is a status response that indicates that the request was processed successfully and that a new resource was created as a result
    }

    public function login(Request $request)
    {   // Validation of the data with the validate of request
        $validatedData = $request->validate([
            // Email not unique for the login
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        // Authentication attempt with the attempt method
        // If authentication fails, we throw an exception
        if (!Auth::attempt($validatedData)) {
            return response()->json(['message' => 'Email ou mot de passe incorrecte'], 401);
        }

        // Method which retrieves the object representing the connected user and loads their favorite gigs
        $user = Auth::user()->load('favoriteGigs');
        // Creation of a token
        $token = $user->createToken('auth_token')->plainTextToken; // The token is then converted into plain text

        // JSON is returned with the access token and token type, usually Bearer.

        return response()->json(['user' => new UserResource($user), 'token' => $token]);
    }

    public function checkValidityToken(Request $request)
    {
        $user = $request->user()->load('favoriteGigs');

        $token = $request->bearerToken();

        return response()->json(['user' => new UserResource($user), 'token' => $token]);
    }

    public function logout(Request $request)
    {
        // Removes all API tokens from the currently authenticated user
        $request->user()->tokens()->delete();

        // Returns a response confirming the disconnection
        return response()->json(['message' => 'logout']);
    }
}

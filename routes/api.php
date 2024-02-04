<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GigController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Models\Review;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// CREATION OF ROUTES FOR GIGS
// Concerning particular routes, for displaying 3 gigs on the home page
Route::get('/gigs/featured-gigs', [GigController::class, 'featuredGigs']);
// Concerning particular routes, for displaying gigs by categories
Route::post('/gigs/category/{categoryId}', [GigController::class, 'getGigsByCategory'])
    ->where('id', '[0-9]+');
// For displaying the gigs of the concerned user
Route::get('/gigs/user/{userId}', [GigController::class, 'getGigsByUser'])
    ->where('id', '[0-9]+');
// Creation of all routes with the http method with the php artisan route:list command
// Accessible to the non-connected
Route::apiResource('/gigs', GigController::class)
    ->only(['index', 'show']);
// Accessible to connected users
Route::middleware(['auth:sanctum', 'checkRole:Super admin,Admin,User'])
    ->group(function () {
        Route::apiResource('/gigs', GigController::class)
            ->except(['index', 'show']);
    });
Route::post('/gigs/searched-gigs', [GigController::class, 'searchedGigs']);

// CREATION OF ROUTES FOR USERS

// For displaying all of an user's favorites
// Accessible to connected users
Route::get('/users/{id}/favorites', [UserController::class, 'getFavorites'])
    ->middleware('auth:sanctum', 'checkRole:Super admin,Admin,User')
    ->where('id', '[0-9]+');
// For displaying an user's profile based on a service
Route::get('/gigs/{gigId}/user', [UserController::class, 'getUserByGig'])
    ->where('id', '[0-9]+');

// A non-connected can see an user's profile
Route::apiResource('/users', UserController::class)->only(['show']);

Route::apiResource('/users', UserController::class)->only(['update'])->middleware('auth:sanctum', 'checkRole:Super admin, Admin, User');
// A connected Super admin / admin can perform the delete and store
Route::middleware(['auth:sanctum', 'checkRole:Super admin, Admin'])
    ->group(function () {
        Route::apiResource('/users', UserController::class)
            ->except(['show', 'update']);
    });


// CREATION OF ROUTES FOR CATEGORIES
// Creation of all routes with the http method with the php artisan route:list command
// A non-connected can see all categories, a specific category
Route::apiResource('/categories', CategoryController::class)->only(['show', 'index']);

// A connected Super admin / admin can update, create or delete a category
Route::middleware(['auth:sanctum', 'checkRole:Super admin, Admin'])
    ->group(function () {
        Route::apiResource('/categories', CategoryController::class)
            ->except(['show', 'index']);
    });

// CREATION OF ROUTES FOR REVIEWS
// For displaying all reviews associated with a gig
Route::get('/gigs/{gigId}/reviews', [ReviewController::class, 'getReviewsByGig'])
    ->where('id', '[0-9]+');
// Creation of all routes with the http method with the php artisan route:list command
// A non-connected can see a specific review
Route::apiResource('/reviews', ReviewController::class)->only(['show']);

// A connected Super admin / admin can create a review
Route::apiResource('/reviews', ReviewController::class)->only(['store'])->middleware('auth:sanctum', 'checkRole:Super admin, Admin, User');
// A connected Super admin / admin can see and delete a review
Route::middleware(['auth:sanctum', 'checkRole:Super admin, Admin'])
    ->group(function () {
        Route::apiResource('/reviews', ReviewController::class)
            ->except(['show', 'update', 'store']);
    });

// CREATION OF ROUTES FOR TAGS
// For displaying all tags associated with a gig
Route::get('/gigs/{gigId}/tags', [TagController::class, 'getTagsByGig'])->where('gigId', '[0-9]+');
// Creation of all routes with the http method with the php artisan route:list command
// A non-connected can see all tags, a specific tag
Route::apiResource('/tags', TagController::class)->only(['show', 'index']);;
// A connected Super admin / admin can update, create or delete a tag
Route::middleware(['auth:sanctum', 'checkRole:Super admin, Admin'])
    ->group(function () {
        Route::apiResource('/tags', TagController::class)
            ->except(['show', 'index']);
    });

// CREATING ROUTES FOR AUTHENTICATIONS
// Route to create a new user
Route::post('/register', [AuthController::class, 'register']);
// Route to log in
Route::post('/login', [AuthController::class, 'login'])->name('user.login');
// Route to log out
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
// Route to check if the token received is valid
Route::post('/check-validity-token', [AuthController::class, 'checkValidityToken'])->middleware('auth:sanctum')->name('user.check');

// CREATING ROUTE FOR FAVORITES
Route::post('/user/{userId}/add-or-delete-favorites/{gigId}', [UserController::class, 'addOrDeleteFavorites'])->middleware('auth:sanctum')->where(['gigId' => '[0-9]+', 'userId' => '[0-9]+']);

// CREATING ROUTE FOR CONTACT FORM
Route::post('/contact', [ContactController::class, 'submitContactForm']);

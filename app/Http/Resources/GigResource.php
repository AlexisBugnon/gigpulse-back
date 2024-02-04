<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class GigResource extends JsonResource
{
    public static $wrap = 'gig';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'picture' => $this->picture,
            'price' => $this->price,
            // Passing the data in camelCase
            'isActive' => $this->is_active,
            // Ternary, use Carbon library (which formats dates)
            'createdAt' => $this->created_at ? Carbon::parse($this->created_at)->translatedFormat('d/m/Y')
                : null,
            'updatedAt' => $this->created_at ? Carbon::parse($this->created_at)->translatedFormat('d/m/Y')
                : null,
            // The belongTo relation allows you to fetch the name of the category
            'category' => $this->category->name,
            'categoryId' => $this->category->id,
            'tags' => $this->whenLoaded('tags', function () {
                return [ 'ids' => $this->tags->pluck('id'), 'name' => $this->tags->pluck('name')] ;
            }),
            // Display the number of associated comments
            'numberOfReviews' => $this->reviews->count(),
            // Display the average service rating
            'averageRating' => $this->average_rating,
            // Display the name and id of the associated user
            'user' => ['id' => $this->user->id, 'email' => $this->user->email, 'name' => $this->user->name, 'profilePicture' => $this->user->profile_picture, 'description' => $this->user->description, 'job' => $this->user->job, 'numberOfGigs' => $this->user->gigs->count(),],

        ];
    }
}

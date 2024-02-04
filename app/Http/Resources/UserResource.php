<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\GigResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public static $wrap = 'user';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'profilePicture' => $this->profile_picture,
            'description' => $this->description,
            'job' => $this->job,
            'role' => $this->role,
            'isActive' => $this->is_active,
            'numberOfGigs' => $this->gigs->count(),
            // Ternary, use Carbon library (which formats dates)
            'createdAt' => $this->created_at ? Carbon::parse($this->created_at)->translatedFormat('d/m/Y')
                : null,
            'gigs' => GigResource::collection($this->gigs),
            'gigsFavorites' => $this->whenLoaded('favoriteGigs', function () {
                if (request()->route()->getName() === 'user.login' || request()->route()->getName() === 'user.check') {
                    return $this->favoriteGigs->pluck('id');
                }
                $gigs = $this->favoriteGigs->load('tags');
                return GigResource::collection($gigs);
            })
        ];
    }
}

<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public static $wrap = 'review';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'gidId' => $this->gig_id,
            'gigTitle' => $this->gig->title,
            'userId' => $this->user_id,
            'userName' => $this->user->name,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'profilePicture' => $this->user->profile_picture,
            // Ternary, use Carbon library (which formats dates)
            'createdAt' => $this->created_at ? Carbon::parse($this->created_at)->translatedFormat('d/m/Y')
                : null,
        ];
    }
}

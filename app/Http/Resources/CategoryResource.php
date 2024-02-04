<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public static $wrap = 'category';

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
            'picture' => $this->picture,
            'numberOfGigs' => $this->gigs->count(),
            // Ternary, use Carbon library (which formats dates)
            'description' => $this->description,
            'slug' => $this->slug,
            'react_icon' => $this->react_icon,
            'createdAt' => $this->created_at ? Carbon::parse($this->created_at)->translatedFormat('d/m/Y')
                : null,
            'updatedAt' => $this->created_at ? Carbon::parse($this->created_at)->translatedFormat('d/m/Y')
                : null,
        ];
    }
}

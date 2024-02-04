<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    public static $wrap = 'tag';

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
            'createdAt' => $this->created_at ? Carbon::parse($this->created_at)->translatedFormat('d/m/Y')
            : null,
        'updatedAt' => $this->created_at ? Carbon::parse($this->created_at)->translatedFormat('d/m/Y')
            : null,
          ];
}
}

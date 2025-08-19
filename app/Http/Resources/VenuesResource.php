<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VenuesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'address' => $this->address,
            'price' => $this->price,
            'category' => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
            ],
            'city' => [
                'id' => $this->city?->id,
                'name' => $this->city?->name,
            ],
            'thumbnail' => $this->primaryImage
                ? asset('storage/' . $this->primaryImage->image_url)
                : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

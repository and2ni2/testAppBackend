<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ManagerRequestsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'full_name' =>  $this->user?->first_name . ' ' . $this->user?->last_name . ' ' . $this->user?->middle_name,
                'email' => $this->user?->email,
                'mobile' => $this->user?->mobile,
            ],
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category?->name,
            ],
            'theme' => $this->theme,
            'message' => $this->originalMessage?->message,
            'file_path' => $this->file_path,
            'closed_at' => $this->closed_at,
            'created_at' => $this->created_at,
        ];
    }
}

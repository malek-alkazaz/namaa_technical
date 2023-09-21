<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            "id"            => $this->id,
            "title"         => $this->title,
            "body"          => $this->body,
            "status"        => $this->status,
            "user"          => ['id' => $this->user->id , 'name' => $this->user->name],
        ];
    }
}

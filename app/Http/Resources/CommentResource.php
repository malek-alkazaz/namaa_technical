<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            "id"                => $this->id,
            "content"           => $this->content,
            "article"           => ['id' => $this->article->id , 'name' => $this->article->title],
            "user"              => ['id' => $this->user->id , 'name' => $this->user->name],
        ];
    }
}

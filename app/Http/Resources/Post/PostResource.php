<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Models\PostImage;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $url = isset($this->image) ? $this->image->url : null;
        
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'image_url' => $url,
            'date' => $this->date,
            'is_liked' => $this->is_liked ?? false,
            'likes_count' => $this->likedUsers->count(),
        ];
    }
}

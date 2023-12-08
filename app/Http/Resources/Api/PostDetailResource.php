<?php

namespace App\Http\Resources\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostDetailResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'writer' => $this->whenLoaded('writer'),
            'news_content' => $this->news_content,
            'created_at' => Carbon::parse($this->created_at)->format('d-m-Y H:i:s'),
            'comments' => $this->whenLoaded('comments', function() {
                return collect($this->comments)->each(function ($comment) {
                    $comment->comentator;
                    return $comment;
                });
            }),
            'total_comments' => $this->whenLoaded('comments', function () {
                return count($this->comments);
            }),
        ];
    }
}

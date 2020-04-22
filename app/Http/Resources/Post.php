<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserResource;
use App\Traits\Aws;


class Post extends JsonResource
{
    use Aws;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->image !== null) {
            $this->image = $this->uriS3('post-images/' . $this->image);
        }
        return [
            'data' => [
                'type' => 'posts',
                'post_id' => $this->id,
                'attributes' => [
                    'posted_by' => new UserResource($this->user),
                    'likes' => new LikeCollection($this->likes),
                    'comments' => new CommentCollection($this->comments),
                    'body' => $this->body,
                    'image' => url($this->image),
                    'posted_at' => $this->created_at->diffForHumans(),
                ]
            ],
            'links' => [
                'self' => url('/posts/' . $this->id)
            ]
        ];
    }
}

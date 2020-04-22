<?php

namespace App\Http\Resources;

use App\Friend;
use App\Http\Resources\Friend as FriendResource;
use App\Http\Resources\UserImage as UserImageResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\Aws;

class User extends JsonResource
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
        $this->coverImage->path = $this->uriS3('user-images/' . $this->coverImage->path);
        $this->profileImage->path = $this->uriS3('user-images/' . $this->profileImage->path);
        return [
            'data' => [
                'type' => 'users',
                'user_id' => $this->id,
                'attributes' => [
                    'name' => $this->name,
                    'friendship' => new FriendResource(Friend::friendship($this->id)),
                    'cover_image' => new UserImageResource($this->coverImage),
                    'profile_image' => new UserImageResource($this->profileImage),
                ]
            ],
            'links' => [
                'self' => url('/users/' . $this->id)
            ]
        ];
    }
}

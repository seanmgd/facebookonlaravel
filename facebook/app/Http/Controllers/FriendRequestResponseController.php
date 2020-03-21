<?php

namespace App\Http\Controllers;

use App\Friend;
use Illuminate\Http\Request;
use \App\Http\Resources\Friend as FriendResource;

class FriendRequestResponseController extends Controller
{
    public function store() {
        $data = request()->validate([
            'user_id'=> '',
            'status'=> '',
        ]);

        $friendRequest = Friend::where('user_id', $data['user_id'])
            ->where('friend_id', auth()->user()->id)
            ->firstOrFail();

        $friendRequest->update(array_merge($data, [
            'confirmed_at' => now(),
        ]));

        return new FriendResource($friendRequest);
    }
}

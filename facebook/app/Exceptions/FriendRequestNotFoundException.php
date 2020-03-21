<?php

namespace App\Exceptions;

use Exception;

class FriendRequestNotFoundException extends Exception
{

    public function render($request)
    {
        return response()->json([
            'errors' => [
                'code' => 404,
                'title' => 'Friend Request not found',
                'detail' => 'Unable to locate the friend request with the given informations'
            ]
        ], 404);
    }
}

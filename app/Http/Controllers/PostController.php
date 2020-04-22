<?php

namespace App\Http\Controllers;

use App\Friend;
use App\Http\Resources\Post as PostResource;
use App\Http\Resources\PostCollection;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return PostCollection
     */
    public function index()
    {
        $friends = Friend::friendships();

        if ($friends->isEmpty()) {
            return new PostCollection(request()->user()->posts);
        }

        return new PostCollection(
            Post::whereIn('user_id', [$friends->pluck('user_id'), $friends->pluck('friend_id')])
                ->get()
        );

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = request()->validate([
            'body' => '',
            'image' => '',
            'height' => '',
            'width' => '',
        ]);

        if (isset($data['image'])) {
            $disk = Storage::disk('s3');

            //Save path to aws s3
            $disk->put('storage/post-images', $data['image']);

            $path = 'storage/post-images/' . $data['image']->hashName();
            $s3_client = $disk->getDriver()->getAdapter()->getClient();
            $command = $s3_client->getCommand(
                'GetObject',
                [
                    'Bucket' => env('AWS_BUCKET'),
                    'Key' => $path,
                    'ResponseContentDisposition' => 'attachment;'
                ]
            );

            $request = $s3_client->createPresignedRequest($command, '+5 minutes');
            $image = (string)$request->getUri();
        }

        $post = request()->user()->posts()->create([
            'body' => $data['body'],
            'image' => $image ?? null,
        ]);

        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserImage as UserImageResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UserImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store()
    {

        $disk = Storage::disk('s3');

        $data = request()->validate([
            'image' => '',
            'width' => '',
            'height' => '',
            'location' => '',
        ]);

        //Save path to aws s3
        $disk->put('storage/user-images', $data['image']);

        $path = 'storage/user-images/' . $data['image']->hashName();
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

        $userImage = auth()->user()->images()->create([
            //Save path string to database path column
            'path' => (string)$request->getUri(),
            'width' => $data['width'],
            'height' => $data['height'],
            'location' => $data['location'],
        ]);

        return new UserImageResource($userImage);
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

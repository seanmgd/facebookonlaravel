<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait Aws
{
    public function uriS3($hashName)
    {
        $path = 'storage/' . $hashName;
        $s3_client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
        $command = $s3_client->getCommand(
            'GetObject',
            [
                'Bucket' => env('AWS_BUCKET'),
                'Key' => $path,
                'ResponseContentDisposition' => 'attachment;'
            ]
        );

        $request = $s3_client->createPresignedRequest($command, '+60 minutes');

        return (string)$request->getUri();
    }

}

<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friends', 'friend_id', 'user_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function likedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'likes', 'user_id', 'post_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(UserImage::class);
    }

    public function uriS3($defaultName) {
        $path = 'storage/user-images/' . $defaultName;
        $s3_client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
        $command = $s3_client->getCommand(
            'GetObject',
            [
                'Bucket' => env('AWS_BUCKET'),
                'Key' => $path,
                'ResponseContentDisposition' => 'attachment;'
            ]
        );

        $request = $s3_client->createPresignedRequest($command, '+5 minutes');

        return (string)$request->getUri();
    }

    public function coverImage(): HasOne
    {
        return $this->hasOne(UserImage::class)
            ->orderByDesc('id')
            ->where('location', 'cover')
            ->withDefault(function ($userImage) {
                $userImage->path = $this->uriS3('default_cover.png');
            });

    }

    public function profileImage(): HasOne
    {
        return $this->hasOne(UserImage::class)
            ->orderByDesc('id')
            ->where('location', 'profile')
            ->withDefault(function ($userImage) {
                $userImage->path = $this->uriS3('default_pp.png');
            });
    }
}

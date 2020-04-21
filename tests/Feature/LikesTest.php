<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Post;

class LikesTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_like_a_post()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $post = factory(Post::class)->create(['id' => 123]);

        $response = $this->post('/api/posts/' . $post->id . '/like')
            ->assertStatus(200);

        $this->assertCount(1, $user->likedPosts);

        $response->assertJson([
            'data' => [
                [
                    'data' => [
                        'type' => 'likes',
                        'like_id' => 1,
                        'attributes' => [],
                    ],
                    'links' => [
                        'self' => url('/posts/123'),
                    ]
                ]
            ],
            'links' => [
                'self' => url('/posts'),
            ]
        ]);
    }

    public function test_posts_are_returned_with_likes()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $post = factory(Post::class)->create(['id' => 123, 'user_id' => $user->id]);
        $this->post('/api/posts/' . $post->id . '/like')
            ->assertStatus(200);

        $response = $this->get('/api/posts')
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'data' => [
                            'type' => 'posts',
                            'attributes' => [
                                'likes' => [
                                  'data' => [
                                      [
                                          'data' => [
                                              'type' => 'likes',
                                              'like_id' => 1,
                                              'attributes' => []
                                          ]
                                      ]
                                  ],
                                    'like_count' => 1,
                                    'user_likes_post' => true,
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    }
}

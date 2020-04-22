<?php

namespace Tests\Feature;

use App\Friend;
use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RetrievePostTest extends TestCase
{
    use RefreshDatabase;


    public function test_a_user_can_only_retrieve_their_posts()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $posts = factory(Post::class, 2)->create();

        $response = $this->get('/api/posts');

        $response->assertStatus(200)
            ->assertExactJson([
               'data' => [],
               'links' => [
                   'self' => url('/posts'),
               ]
            ]);
    }
}

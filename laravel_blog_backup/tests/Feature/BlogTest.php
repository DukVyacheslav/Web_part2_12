<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Blog;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_creation()
    {
        $response = $this->post('/blog', [
            'title' => 'Test Post',
            'content' => 'Test Content'
        ]);
        $response->assertStatus(302); // Проверка редиректа
        $this->assertDatabaseHas('blogs', ['title' => 'Test Post']);
    }

    public function test_blog_deletion()
    {
        $post = Blog::factory()->create();
        $response = $this->delete('/blog/' . $post->id);
        $response->assertStatus(302);
        $this->assertDatabaseMissing('blogs', ['id' => $post->id]);
    }
}

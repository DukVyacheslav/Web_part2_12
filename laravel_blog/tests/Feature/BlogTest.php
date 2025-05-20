<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Blog;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_blog_post()
    {
        $data = [
            'name' => 'Тестовый пост',
            'text' => 'Контент тестового поста',
        ];

        $response = $this->post('/blog', $data);

        $response->assertStatus(302); // редирект после успешного создания
        $this->assertDatabaseHas('blogs', [
            'name' => $data['name'],
            'text' => $data['text'],
        ]);
    }

    /** @test */
    public function it_deletes_a_blog_post()
    {
        $post = Blog::factory()->create();

        $response = $this->delete("/blog/{$post->id}");

        $response->assertStatus(302); // редирект после успешного удаления
        $this->assertDatabaseMissing('blogs', ['id' => $post->id]);
    }

    /** @test */
    public function it_validates_blog_post_creation()
    {
        $response = $this->post('/blog', []);

        $response->assertSessionHasErrors(['name', 'text']);
    }
}

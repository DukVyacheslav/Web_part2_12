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
            'title' => 'Тестовый пост',
            'content' => 'Контент тестового поста',
        ];

        $response = $this->post('/admin/blog/upload', $data);

        $response->assertStatus(302); // редирект после успешного создания
        $this->assertDatabaseHas('blogs', [
            'title' => $data['title'],
            'content' => $data['content'],
        ]);
    }
}

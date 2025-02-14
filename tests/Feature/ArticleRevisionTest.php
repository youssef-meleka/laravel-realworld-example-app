<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleRevisionTest extends TestCase
{
    public function test_list_revisions()
    {
        $article = Article::factory()->create();
        $revisions = ArticleRevision::factory()->count(3)->create(['article_id' => $article->id]);

        $response = $this->getJson("/api/articles/{$article->id}/revisions");

        $response->assertStatus(200)
                ->assertJsonCount(3);
    }

    public function test_revert_to_revision()
    {
        $article = Article::factory()->create();
        $revision = ArticleRevision::factory()->create(['article_id' => $article->id]);

        $response = $this->postJson("/api/articles/{$article->id}/revisions/{$revision->id}/revert");

        $response->assertStatus(200)
                ->assertJson(['message' => 'Article reverted successfully']);
    }
}

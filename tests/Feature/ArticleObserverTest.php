<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Article;
use App\Models\ArticleRevision;

class ArticleObserverTest extends TestCase
{
    public function test_revision_is_created_when_article_is_updated()
    {
        $article = Article::factory()->create([
            'title' => 'Old Title',
            'body' => 'Old Body',
        ]);

        // Update the article
        $article->update([
            'title' => 'New Title',
            'body' => 'New Body',
        ]);

        // Check that a revision was created
        $this->assertCount(1, $article->revisions);

        // Verify the revision contains the old data
        $revision = $article->revisions->first();
        $this->assertEquals('Old Title', $revision->title);
        $this->assertEquals('Old Body', $revision->body);
    }
}

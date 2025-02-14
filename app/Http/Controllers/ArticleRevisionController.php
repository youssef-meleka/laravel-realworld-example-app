<?php

use App\Models\Article;
use App\Models\ArticleRevision;
use Illuminate\Http\Request;

class ArticleRevisionController extends Controller
{
    // List all revisions for an article
    public function index(Article $article)
    {
        return response()->json($article->revisions);
    }

    // Show a specific revision
    public function show(Article $article, ArticleRevision $revision)
    {
        return response()->json($revision);
    }

    // Revert an article to a specific revision
    public function revert(Article $article, ArticleRevision $revision)
    {
        // Check authorization (e.g., only the article author or admin can revert)
        $this->authorize('update', $article);

        $article->update([
            'title' => $revision->title,
            'body' => $revision->body,
        ]);

        return response()->json(['message' => 'Article reverted successfully']);
    }
}

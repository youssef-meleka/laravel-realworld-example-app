<?php

namespace App\Http\Controllers;

use App\Http\Requests\Article\DestroyRequest;
use App\Http\Requests\Article\FeedRequest;
use App\Http\Requests\Article\IndexRequest;
use App\Http\Requests\Article\StoreRequest;
use App\Http\Requests\Article\UpdateRequest;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\User;
use App\Services\ArticleService;
use App\Models\ArticleRevision;
use Illuminate\Http\Request;


class ArticleController extends Controller
{
    protected Article $article;
    protected ArticleService $articleService;
    protected User $user;

    public function __construct(Article $article, ArticleService $articleService, User $user)
    {
        $this->article = $article;
        $this->articleService = $articleService;
        $this->user = $user;
    }

    public function index(IndexRequest $request): ArticleCollection
    {
        return new ArticleCollection($this->article->getFiltered($request->validated()));
    }

    public function feed(FeedRequest $request): ArticleCollection
    {
        return new ArticleCollection($this->article->getFiltered($request->validated()));
    }

    // public function show(Article $article): ArticleResource
    // {
    //     return $this->articleResponse($article);
    // }

    public function store(StoreRequest $request): ArticleResource
    {
        $article = auth()->user()->articles()->create($request->validated()['article']);

        $this->syncTags($article);

        return $this->articleResponse($article);
    }

    public function update(Article $article, UpdateRequest $request): ArticleResource
    {
        $article->update($request->validated()['article']);

        $this->syncTags($article);

        return $this->articleResponse($article);
    }

    public function destroy(Article $article, DestroyRequest $request): void
    {
        $article->delete();
    }

    public function favorite(Article $article): ArticleResource
    {
        $article->users()->attach(auth()->id());

        return $this->articleResponse($article);
    }

    public function unfavorite(Article $article): ArticleResource
    {
        $article->users()->detach(auth()->id());

        return $this->articleResponse($article);
    }

    protected function syncTags(Article $article): void
    {
        $this->articleService->syncTags($article, $this->request->validated()['article']['tagList'] ?? []);
    }

    protected function articleResponse(Article $article): ArticleResource
    {
        return new ArticleResource($article->load('user', 'users', 'tags', 'user.followers'));
    }

    // Blade functions
    
    public function show($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        $revisions = $article->revisions()->orderBy('updated_at', 'desc')->get();

        return view('articles.show', [
            'article' => $article,
            'revisions' => $revisions,
        ]);
    }

    public function revert($slug, $revisionId)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        $revision = ArticleRevision::findOrFail($revisionId);

        // Check if the current user is the article author
        if (auth()->user()->id !== $article->user_id) {
            return redirect()->back()->with('error', 'You are not authorized to revert this article.');
        }

        // Revert the article to the selected revision
        $article->update([
            'title' => $revision->title,
            'body' => $revision->body,
        ]);

        return redirect()->back()->with('success', 'Article reverted successfully!');
    }
}

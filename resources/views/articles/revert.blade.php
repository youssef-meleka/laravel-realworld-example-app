@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Revert Article: {{ $article->title }}</h2>
    <p>Are you sure you want to revert this article to the selected revision?</p>

    <div class="card">
        <div class="card-body">
            <h4>{{ $revision->title }}</h4>
            <p>{{ $revision->body }}</p>
            <p><strong>Updated At:</strong> {{ $revision->updated_at->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>

    <form action="{{ route('articles.revert', [$article->slug, $revision->id]) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger">Confirm Revert</button>
        <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="article-page">
    <h1>{{ $article->title }}</h1>
    <p>{{ $article->body }}</p>

    <!-- Display Revision History -->
    <div class="revision-history">
        <h3>Revision History</h3>
        <ul>
            @foreach ($revisions as $revision)
                <li>
                    <div>
                        <strong>Title:</strong> {{ $revision->title }}
                    </div>
                    <div>
                        <strong>Body:</strong> {{ $revision->body }}
                    </div>
                    <div>
                        <strong>Updated At:</strong> {{ $revision->updated_at->format('Y-m-d H:i:s') }}
                    </div>
                    @if (auth()->check() && auth()->user()->id === $article->user_id)
                        <form action="{{ route('articles.revisions.revert', [$article->slug, $revision->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">Revert</button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection

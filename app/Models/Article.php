<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'body'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(ArticleRevision::class);
    }

    public function getFiltered(array $filters): Collection
    {
        return self::query()
            ->filter($filters, 'tag', 'tags', 'name')
            ->filter($filters, 'author', 'user', 'username')
            ->filter($filters, 'favorited', 'users', 'username')
            ->when(isset($filters['offset']), function (Builder $query) use ($filters) {
                $query->offset($filters['offset'])->limit($filters['limit'] ?? 10);
            })
            ->with(['user', 'users', 'tags', 'user.followers'])
            ->get();
    }

    public function scopeFilter(Builder $query, array $filters, string $key, string $relation, string $column): Builder
    {
        return $query->when(isset($filters[$key]), function (Builder $q) use ($filters, $relation, $column, $key) {
            $q->whereRelation($relation, $column, $filters[$key]);
        });
    }

    public function setTitleAttribute(string $title): void
    {
        $this->attributes['title'] = $title;
        $this->attributes['slug'] = Str::slug($title);
    }
}

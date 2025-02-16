<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
// use Tymon\JWTAuth\Contracts\JWTSubject;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
class User extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $fillable = ['username', 'email', 'password', 'bio', 'images'];

    protected $visible = ['username', 'email', 'bio', 'images'];

    public function getRouteKeyName(): string
    {
        return 'username';
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function favoritedArticles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class);
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id');
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id');
    }

    public function doesUserFollowAnotherUser(int $followerId, int $followingId): bool
    {
        return self::where('id', $followerId)->whereHas('following', function ($query) use ($followingId) {
            $query->where('id', $followingId);
        })->exists();
    }

    public function doesUserFollowArticle(int $userId, int $articleId): bool
    {
        return self::where('id', $userId)->whereHas('favoritedArticles', function ($query) use ($articleId) {
            $query->where('id', $articleId);
        })->exists();
    }

    public function setPasswordAttribute(string $password): void
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }
}

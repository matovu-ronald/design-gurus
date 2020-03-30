<?php

namespace App\Models\Traits;

use App\Models\Like;

trait Likeable
{
    public static function bootLikeable()
    {
        static::deleting(function ($model) {
            $model->removeLikes();
        });
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Like model.
     */
    public function like()
    {
        if (! auth()->check()) {
            return;
        }

        // Check if the current user had already liked the model
        if ($this->isLikedByUser(auth()->id())) {
            return;
        }

        // Create like in the database
        $this->likes()->create(['user_id' => auth()->id()]);
    }

    /**
     * Unlike model.
     */
    public function unlike()
    {
        if (! auth()->check()) {
            return;
        }

        // Check if the current user had already liked the model
        if (! $this->isLikedByUser(auth()->id())) {
            return;
        }

        $this->likes()
            ->where('user_id', auth()->id())
            ->delete();
    }

    public function isLikedByUser($userId)
    {
        return (bool) $this->likes()
            ->where('user_id', $userId)
            ->count();
    }

    public function removeLikes()
    {
        if ($this->likes()->count()) {
            $this->likes()->delete();
        }
    }
}

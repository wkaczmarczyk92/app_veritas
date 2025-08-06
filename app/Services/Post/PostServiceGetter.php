<?php

namespace App\Services\Post;

use App\Models\Post;

class PostServiceGetter
{
    public function __invoke($display = false)
    {
        if ($display) {
            return Post::with('post_labels')->get();
        }

        $current_date = date('Y-m-d');

        $posts = Post::with('post_labels')
            ->where('type', '=', 'publish')
            ->where(function ($query) use ($current_date) {

                $query->where(function ($query) {
                    $query->whereNull('start_at')
                        ->whereNull('end_at');
                });

                $query->orWhere(function ($query) use ($current_date) {
                    $query->whereDate('start_at', '<=', $current_date)
                        ->whereDate('end_at', '>=', $current_date);
                });

                $query->orWhere(function ($query) use ($current_date) {
                    $query->whereNull('start_at')
                        ->whereDate('end_at', '>=', $current_date);
                });

                $query->orWhere(function ($query) use ($current_date) {
                    $query->whereNull('end_at')
                        ->whereDate('start_at', '<=', $current_date);
                });
            })
            ->orderBy('order')
            ->get();

        return $posts;
    }
}

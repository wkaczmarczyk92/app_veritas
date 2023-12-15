<?php

namespace App\Services;

use App\Models\Offer;

class OfferService
{
    public function latest(int $take = 10) {
        return Offer::with('user.user_profiles')
            ->orderBy('created_at', 'desc')
            ->take($take)
            ->get();
    }
}
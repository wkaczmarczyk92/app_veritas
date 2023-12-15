<?php

namespace App\Services;
use App\Models\PayoutRequest;

class PayoutRequestService
{
    public function latest_incomplete(int $take = 5) {
        return PayoutRequest::with('user_has_bonus.user.user_profiles')
                    ->whereHas('user_has_bonus', function($query) {
                        $query->where('completed', false)
                            ->where('in_progress', true);
                    })
                    ->latest()
                    ->take($take)
                    ->get();
    }
}
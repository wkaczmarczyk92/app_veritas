<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use App\Models\User;
use App\Models\Test\OralExam;
use App\Models\Test\TestResult;
use Carbon\Carbon;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = auth()->user();
        $test_user = auth()->check() ? $user->pesel == 12312312322 : false;


        $tests = TestResult::where('user_id', auth()->id())->get();
        // dd($tests);

        $test_passed = $tests->contains('is_passed', true);
        $oral_exam_passed = OralExam::where('is_passed', true)->where('user_id', auth()->id())->count();
        $has_oral_exam = OralExam::where('is_passed', null)->where('user_id', auth()->id())->count();
        $is_month_passed = $test_passed ? $tests->filter(fn ($item) => $item->is_passed == true) : false;

        // dd($is_month_passed);

        if (gettype($is_month_passed) == 'object') {
            $first_date = Carbon::parse($is_month_passed[0]->created_at);
            // dd($first_date);
            $second_date = date('Y-m-d');

            $is_month_passed = $first_date->diffInMonths($second_date) >= 1;
        }

        // dd($is_month_passed);

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'test_user' => $test_user,
            'god_mode' => $user ? $user->hasRole('god_mode') : false,
            'is_super_admin' => $user ? $user->hasRole('super-admin') : false,
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'mimic_uuid' => session('auth_mimic_uuid') ?? null,
            'maintenance_mode_status' => app()->isDownForMaintenance(),
            'user_test' => [
                'test_passed' => $test_passed ?? false,
                'oral_exam_passed' => $oral_exam_passed ?? false,
                'has_oral_exam' => $has_oral_exam ?? false,
                'is_month_passed' => $is_month_passed ?? false
            ]
        ]);
    }
}

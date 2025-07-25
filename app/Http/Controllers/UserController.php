<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\User;
use App\Models\Level;
use App\Models\UserPoint;
use App\Models\UserProfile;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

use App\Helpers\CURLRequest;
use Illuminate\Support\Facades\Auth;

use App\Models\ReadyToDepartureDate;
use Exception;

use App\Helpers\Response;

use Illuminate\Support\Facades\Hash;
use App\Models\CRMRecruiter;


use Spatie\Permission\Models\Role;
use App\Models\Common\CompanyBranch;
// use App\Models\Bonus;
use App\Models\UserHasBonus;

use App\Http\Requests\User\UserStoreRequest;
use App\Services\User\UserStoreService;

use App\Http\Requests\User\CourseModerator\StoreRequest as CourseModeratorStoreRequest;
use App\Services\User\SyncUserProfileWithCRMAccountService;
use App\Services\User\Admin\UsersSearchService;

class UserController extends Controller
{
    public function admin_search_index(Request $request) {
        return (new UsersSearchService)->index($request);
    }


    public function get()
    {
        $user = User::with(['user_profiles', 'user_points', 'ready_to_departure_dates', 'user_profile_image', 'user_has_bonus' => function ($query) {
            // $query->where('completed', false)
            //     ->where('in_progress', false);
        }])->find(Auth::user()->id);

        return response()->json($user);
    }

    public function index(Request $request)
    {
        $order_by = $request->order_by ?? 'full_name';
        $order = $request->order ?? 'asc';

        $users = User::with(['user_profiles', 'user_profile_image', 'roles'])
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->select('users.*', DB::raw("CONCAT(user_profiles.last_name, ' ', user_profiles.first_name) as full_name"))
            ->whereHas('roles', function ($query) {
                $query->where('name', 'user');
            });


        // if ($request->search != '') {
        //     $users->whereHas('user_profiles', function ($query) use ($request) {
        //         $query->where('first_name', 'like', "%{$request->search}%")
        //             ->orWhere('last_name', 'like', "%{$request->search}%")
        //             ->orWhere('email', 'like', "%{$request->search}%")
        //             ->orWhere('phone_number', 'like', "%{$request->search}%")
        //             ->orWhere('recruiter_first_name', 'like', "%{$request->search}%")
        //             ->orWhere('recruiter_last_name', 'like', "%{$request->search}%")
        //             ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE '%{$request->search}%'")
        //             ->orWhereRaw("CONCAT(last_name, ' ', first_name) LIKE '%{$request->search}%'")
        //             ->orWhereRaw("CONCAT(recruiter_first_name, ' ', recruiter_last_name) LIKE '%{$request->search}%'")
        //             ->orWhereRaw("CONCAT(recruiter_last_name, ' ', recruiter_first_name) LIKE '%{$request->search}%'");
        //     });

        //     $users->orWhere('pesel', 'like', "%{$request->search}%");
        // }

        // if ($request->current_points != '') {
        //     $users->whereHas('user_profiles', function ($query) use ($request) {
        //         $query->where('current_points', '>=', $request->current_points);
        //     });
        // }

        // if ($request->total_days != '') {
        //     $users->whereHas('user_profiles', function ($query) use ($request) {
        //         $query->where('total_days', '>=', $request->total_days);
        //     });
        // }

        $users->orderBy('id', 'asc');

        $users = $users->take(3000)->get();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'levels' => Level::all()
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with(['user_profiles', 'password_requests' => function ($query) {
            $query->where('active', true);
        }, 'ready_to_departure_dates', 'user_profile_image', 'user_points' => function ($query) {
            $query->latest()
                ->offset(0)
                ->limit(10)
                ->get();
        }])->find($id);

        $user_points_records_count =  UserPoint::where('user_id', $id)->count();

        $user_bonuses = UserHasBonus::where('user_id', $user->id)->where('bonus_status_id', 5)->get();

        return Inertia::render('Admin/Users/Show', [
            'user' => $user,
            'levels' => Level::all(),
            'user_points_records_count' => $user_points_records_count,
            'user_bonus' => $user_bonuses
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user_id = $request->id;

        try {
            $validate = $request->validate([
                'pesel' => [
                    'required',
                    'numeric',
                    'digits:11',
                    Rule::unique('users')->ignore($request->id)
                ],
                'user_profiles.first_name' => 'required|string',
                'user_profiles.last_name' => 'required|string',
                'user_profiles.email' => [
                    'required',
                    'email'
                ],
                'user_profiles.phone_number' => [
                    'required',
                    'string'
                ],
                'ready_to_departure_dates.departure_date' => 'nullable|date|date_format:Y-m-d'

            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()]);
        }

        $user = User::with(['user_profiles', 'ready_to_departure_dates'])->find($user_id);

        $user->pesel = $request->pesel;
        $user->user_profiles->first_name = $request->user_profiles['first_name'];
        $user->user_profiles->last_name = $request->user_profiles['last_name'];
        $user->user_profiles->email = $request->user_profiles['email'];
        $user->user_profiles->phone_number = $request->user_profiles['phone_number'];

        DB::beginTransaction();

        try {
            $user->save();
            $user->user_profiles->save();

            $departure_date = '';

            if ($request->ready_to_departure_dates['departure_date'] != null) {
                ReadyToDepartureDate::updateOrCreate([
                    'user_id' => $user_id
                ], [
                    'departure_date' => $request->ready_to_departure_dates['departure_date']
                ]);

                $departure_date = $request->ready_to_departure_dates['departure_date'];
            } else {
                if ($user->ready_to_departure_dates != null) {
                    $ready_to_departure = ReadyToDepartureDate::find($user->ready_to_departure_dates->id)->delete();
                    $departure_date = null;
                }
            }

            // $arr = [
            //     'section' => 'personal_data',
            //     'form' => [
            //         'crt_first_name' => $user->user_profiles->first_name,
            //         'crt_last_name' => $user->user_profiles->last_name,
            //         'crt_main_email' => $user->user_profiles->email,
            //         'crt_main_phone_number' => $user->user_profiles->phone_number,
            //         'crt_id_caretaker' => $user->user_profiles->crt_id_caretaker,
            //         'crt_pesel' => $user->pesel
            //     ],
            //     'indexValue' => $user->user_profiles->crt_id_caretaker,
            //     'caretakerHpId' => null
            // ];

            // $curl_request = new CURLRequest;
            // $departure_response = $curl_request->caretaker_departure_date($departure_date, $user->user_profiles->crt_id_caretaker);
            // $response = $curl_request->update_caretaker_data($arr);

            // if (!$response->success) {
            //     throw new Exception('CRM Update failed.');
            // }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            return response()->json(['success' => false, 'msg' => 'Wystąpił błąd podczas połączenia. Spróbuj ponownie później.']);
        }

        return response()->json(['success' => true]);
    }

    public function create_as_course_moderator()
    {
        return Inertia::render('CourseModerator/User/CreateForm', [
            'company_branches' => CompanyBranch::all(),
            'roles' => Role::where('show_course', true)->get()
        ]);
    }

    public function index_course_moderator()
    {
        // dd(User::with(['user_profiles', 'roles', 'company_branches', 'last_login' => function ($query) {
        //     $query->latest()->first();
        // }])
        //     ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
        //     ->select('users.*')
        //     ->whereHas('roles', function ($query) {
        //         $query->whereIn('name', ['recruiter', 'super-visor', 'team-leader', 'recruiter-assistant', 'course_moderator']);
        //     })->orderByRaw('CONCAT(user_profiles.last_name, " ", user_profiles.first_name) ASC')->paginate(50));

        return Inertia::render('CourseModerator/User/Users');
    }

    public function recruiters()
    {
        return response()->json([
            'users' => User::with(['user_profiles', 'roles', 'company_branches', 'last_login' => function ($query) {
                $query->latest()->first();
            }])
                ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
                ->select('users.*')
                ->whereHas('roles', function ($query) {
                    $query->whereIn('name', ['recruiter', 'super-visor', 'team-leader', 'recruiter-assistant', 'course_moderator']);
                })->orderByRaw('CONCAT(user_profiles.last_name, " ", user_profiles.first_name) ASC')->paginate(50)
        ]);
    }

    public function create() {
        return Inertia::render('Admin/Users/Create', [
            'roles' => Role::all()
        ]);
    }

    public function store(UserStoreRequest $request) {
        return (new UserStoreService)($request->validated());
    }

    public function store_as_course_moderator(CourseModeratorStoreRequest $request)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $user_profile = new UserProfile([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'crt_id_user_recruiter' => $request->crm_id
            ]);

            $user->user_profiles()->save($user_profile);
            // $user->assignRole('recruiter');

            if (!empty($request->roles)) {
                $user->roles()->attach($request->roles);
            } else {
                $user->assignRole('recruiter');
            }

            if (!empty($request->company_branches)) {
                $user->company_branches()->attach($request->company_branches);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return Response::danger('Wystąpił błąd podczas zapisu. Spróbuj ponownie później.', e: $e);
        }

        return Response::success('Użytkownik został dodany.');
    }

    public function course_moderator_show(int $id)
    {
        $user = User::with(['user_profiles', 'company_branches', 'roles'])
            ->find($id);

        // dd($user);

        if (!$user) {
            abort(404, 'Not found.');
        }

        return Inertia::render('CourseModerator/User/User', [
            'user' => $user
        ]);
    }

    public function update_crm_id(Request $request, int $id)
    {
        $recruiter = CRMRecruiter::on('crm_database')
            ->where('usr_email', $request->email)
            ->first();

        if ($recruiter) {
            UserProfile::where('user_id', $id)
                ->update([
                    'crt_id_user_recruiter' => $recruiter->usr_id_user
                ]);

            return Response::success('Konta zostały połączone.', [
                'crm_id' => $recruiter->usr_id_user
            ]);
        } else {
            return Response::danger('Nie znaleziono rekrutera o podanym adresie email.');
        }
    }

    public function promote_to_premium(int $user_id) {
        return (new SyncUserProfileWithCRMAccountService)($user_id);
    }
}

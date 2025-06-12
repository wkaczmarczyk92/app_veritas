<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Helpers\Response as ResponseHelper;
use Illuminate\Support\Str;
use App\Models\Token;
use App\Mail\RegisterVerificationMail;

use App\Models\UserProfile;
use Illuminate\Support\Facades\Mail;

use App\Http\Requests\User\RegisterStoreRequest;
use App\Helpers\SMS;

class RegisterController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function verification(string $register_url_token, int $p)
    {
        $token = Token::where('token', $register_url_token)->first();

        // dd($token);

        // if (!$token || !$token->active) {
        //     return redirect()->route('login');
        // }

        return Inertia::render('Auth/SMSVerification', [
            'p' => $p
        ]);
    }

    public function verify(Request $request, int $p)
    {
        $user = User::find($p);
        $user->load(['tokens' => function ($query) {
            $query->whereIn('token_type_id', [1, 2])->where('active', true);
        }, 'user_profiles']);

        foreach ($user->tokens as $token) {
            if (!$token->active) {
                return ResponseHelper::danger('Kod jest niekatywny. Skontaktuj się z administracją serwisu.');
            }
        }

        $code = $user->tokens->filter(fn($item) => $item->token_type_id == 1)->values()[0];

        if ($code && $code->token == $request->code) {
            foreach ($user->tokens as $token) {
                $token->active = false;
                $token->save();
            }

            $user->active = true;
            $user->activated_at = now();
            $user->save();

            return ResponseHelper::success('Konto zostało aktywowane.');
        }

        return ResponseHelper::danger('Nieprawidłowy token.');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'pesel' => $request->pesel,
                'active' => false,
                'password' => Hash::make($request->password)
            ]);

            // dd($user);

            $user_profile = UserProfile::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone_number' => $request->phone_number
            ]);

            do {
                $token = rand(100000, 999999);
            } while (Token::where('token', $token)->exists());

            $register_token = Token::create([
                'token' => $token,
                'token_type_id' => 1,
            ]);

            $user->tokens()->save($register_token);

            do {
                $token = Str::random(150);
            } while (Token::where('token', $token)->exists());

            $register_url_token = Token::create([
                'token' => $token,
                'token_type_id' => 2,
            ]);

            $user->tokens()->save($register_url_token);
            $user->assignRole('user');

            $sms = new SMS;
            $sms->params['to'] = $request->phone_number;
            $sms->params['message'] = "Twój kod do aktywacji konta: {$register_token->token}";
            $sms_result = $sms->send();

            if (!$sms_result) {
                throw new Exception('SMS failed.');
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return ResponseHelper::danger('Coś poszło nie tak podczas próby rejestracji. Spróbuj ponownie później.', e: $e);
        }

        // return redirect()->route('register.verification', [
        //     'register_url_token' => $register_url_token
        // ]);
        return ResponseHelper::success('Rejestracja przebiegła pomyślnie. Na Twój numer telefonu został wysłany kod aktywacyjny.', [
            'register_url_token' => $register_url_token->token,
            'p' => $user->id
        ]);
    }
}

<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Inertia\Inertia;
use App\Models\User;
use App\Helpers\Response;
use App\Models\Test\OralExam;
use App\Helpers\Transaction;
use Illuminate\Database\QueryException;

use Illuminate\Support\Facades\Mail;
use App\Mail\MailAfterSignToOralExam;
use App\Helpers\CURLRequest;
use Illuminate\Support\Facades\DB;

class OralExamController extends Controller
{
    public function rules() {
        return Inertia::render('User/OralExam/Rules');
    }

    public function index()
    {
        $oral_exams = OralExam::with([
            'user',
            'user.user_profiles',
            'user.user_profile_image'
        ])
            ->whereNotNull('is_passed')->orderBy('updated_at', 'desc')->get();

        return Inertia::render('Admin/OralExam/Index', [
            'oral_exams' => $oral_exams
        ]);
    }

    public function index_create()
    {
        $users = $this->get_available_users();

        $oral_exams = $this->get_exams();

        return Inertia::render('Admin/OralExam/Index.create', [
            'users' => $users,
            'oral_exams' => $oral_exams
        ]);
    }

    public function download_selected_date(Request $request)
    {
        // dd($request->all());
        $taken_time = OralExam::where('exam_at', $request->date)->pluck('time');
        return Response::success('', ['taken_time' => $taken_time]);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $oral_exam = OralExam::create([
                'user_id' => $request->user_id ?? auth()->id(),
                'time' => $request->time,
                'exam_at' => $request->date,
                'created_by' => auth()->id()
            ]);

            if (app()->environment('prodution') && auth()->user()->hasRole('user')) {
                $email = 'w.kaczmarczyk@grupa-veritas.pl';

                $oral_exam->load([
                    'user',
                    'user.user_profiles',
                ]);

                if (!Mail::to($email)->send(new MailAfterSignToOralExam($oral_exam))) {
                    throw new \Throwable('Wystąpił błąd podczas zapisywania na egzamin ustny. Spróbuj ponownie później.');
                }
            }

            DB::commit();
        } catch (QueryException $e) {
            DB::rollback();
            if ($e->errorInfo[1] == 1062) {
                // Duplikat - termin już zajęty
                return Response::danger('Niestety termin został już zajęty.');
            }

            throw $e; // inny błąd – przekaż dalej
        } catch (\Throwable $th) {
            DB::rollback();
            return Response::danger('Wystąpił nieoczekiwany błąd. Spróbuj ponownie później.', e: $th);
        }

        return Response::success('Termin został zarezerwowany.', [
            'oral_exam' => $oral_exam,
            'oral_exams' => $this->get_exams(),
        ]);
    }

    public function destroy(Request $request)
    {
        try {
            OralExam::whereIn('id', $request->ids)->delete();
        } catch (\Throwable $th) {
            return Response::danger(' Coś poszło nie tak podczas usuwania terminów. Spróbuj ponownie później.', e: $th);
        }

        return Response::success('Wybrane terminy zostały poprawnie usunięte.', [
            'users' => $this->get_available_users()
        ]);
    }

    public function set_as_passed(Request $request)
    {
        try {
            $oral_exam = OralExam::find($request->id);
            $oral_exam->score = 100;
            $oral_exam->is_passed = true;

            $user = User::with([
                'user_profiles'
            ])->find($oral_exam->user_id);

            $curl_request = new CURLRequest();
            $response = $curl_request->uber_gering($user);

            // dd($response);

            if (!$response->success) {
                throw new \Exception('Nie udało się przesłać danych do CRM. Spróbuj ponownie później.');
            }

            $oral_exam->save();
        } catch (\Throwable $th) {
            return Response::danger('Coś poszło nie tak, spróbuj ponownie później.', e: $th);
        }

        return Response::success('Test został oznaczony jako zaliczony.', [
            'curl_request' => $curl_request
        ]);
    }

    public function set_as_failure(Request $request)
    {
        try {
            $oral_exam = OralExam::find($request->id);
            $oral_exam->score = 0;
            $oral_exam->is_passed = false;

            $oral_exam->save();
        } catch (\Throwable $th) {
            return Response::danger('Coś poszło nie tak, spróbuj ponownie później.', e: $th);
        }

        return Response::success('Test został oznaczony jako niezaliczony.', [
            'users' => $this->get_available_users()
        ]);
    }

    public function get_available_users()
    {
        return User::with([
            'user_profiles',
            'user_profile_image',
        ])
            ->whereHas('test_results', function ($query) {
                $query->where('is_passed', true);
            })
            ->whereDoesntHave('oral_exams', function ($query) {
                // odrzucamy użytkowników, którzy mają choć jeden rekord z NULLem
                $query->whereNull('is_passed');
            })
            ->where(function ($query) {
                $query->whereDoesntHave('oral_exams') // brak rekordów
                    ->orWhereDoesntHave('oral_exams', function ($q) {
                        $q->where('is_passed', true)
                            ->orWhereNull('is_passed');
                    });
            })
            ->get();
    }

    public function get_exams()
    {
        return OralExam::with([
            'user',
            'user.user_profiles',
            'user.user_profile_image'
        ])
            ->where('exam_at', '>=', date('Y-m-d'))
            ->where(function ($query) {
                $query->where('is_passed', null);
            })
            ->orderBy('exam_at')
            ->orderBy('time')
            ->get();
    }
}

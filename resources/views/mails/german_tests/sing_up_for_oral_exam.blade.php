<div>
    Nowy termin egzaminu ustnego został ustalony na: {{ date('Y-m-d', strtotime($data->exam_at)) }}, {{ $data->time }}:00
    <br>
    <a href="https://app.veritas.pl/uzytkownik/{{ $data->user->id }}">{{ $data->user->user_profiles->first_name }} {{ $data->user->user_profiles->last_name }}</a>
</div>
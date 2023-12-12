<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nowe zgłoszenie opiekunki w aplikacji na oferty pracy</title>
</head>
<style>

.bg-test {
    background-color: red;
}

</style>
<body>
    
Masz nowe zgłoszenie opiekunki na oferte<br>
<a href="{{ $data['caretaker_crm_url'] }}">Link do profilu opiekunki w CRM veritas</a><br>
<a href="{{ $data['caretaker_app_url'] }}">Link do profilu opiekunki w App Veritas</a><br>
<br>

Opiekunka {{ $data['first_name'] }} {{ $data['last_name'] }} zgłosiła się na zlecenia:<br>

@foreach ($data['offers'] as $offer)
<table>
    <tr>
        <td>
            Numer HP rodziny
        </td>
        <td>
            {{ $offer['hp_code'] }}
        </td>
    </tr>
    <tr>
        <td>
            Data zgłoszenia
        </td>
        <td>
            {{ $offer['created_at'] }}
        </td>
    </tr>
    <tr>
        <td>
            <a href="https://local.grupa-veritas.pl/#/rodziny/{{ $offer['crm_family_id'] }}">Link do rodziny w CRM</a>
        </td>
    </tr>
</table>
@endforeach




</body>
</html>
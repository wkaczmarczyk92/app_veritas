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

table,
table tr,
table td {
    border-collapse: collapse;
}

td {
    border: 1px solid black;
}

.divider {
    height: 10px;
    background: gray;
}

</style>
<body>

Masz nowe zgłoszenie opiekunki na oferte<br>
<a href="{{ $data['caretaker_crm_url'] }}">Link do profilu opiekunki w CRM veritas</a><br>
<a href="{{ $data['caretaker_app_url'] }}">Link do profilu opiekunki w App Veritas</a><br>
<br>

Opiekunka {{ $data['first_name'] }} {{ $data['last_name'] }} zgłosiła się na zlecenia:<br>

@foreach ($data['offers'][0] as $offer)
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
        <td></td>
    </tr>
    <tr>
        <td colspan="2" class="divider"></td>
    </tr>
</table>
@endforeach




</body>
</html>

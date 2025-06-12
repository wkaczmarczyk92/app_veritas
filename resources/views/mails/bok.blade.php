<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOK E-mail</title>
</head>
<style>
    .bg-test {
        background-color: red;
    }
</style>

<body>

    <table>
        <tr>
            <td>Temat wiadomości:</td>
            <td>{{ $data['subject'] }}</td>
        </tr>
        <tr>
            <td>Treść wiadomości:</td>
            <td>{{ $data['msg'] }}</td>
        </tr>
        @if ($data['subject_id'] == 8)
        <tr>
            <td>Właściciel konta:</td>
            <td>{{ $data['bank_account']['owner_name'] }}</td>
        </tr>
        <tr>
            <td>Rodzaj konta:</td>
            <td>{{ $data['bank_account']['account_type'] }}</td>
        </tr>
        <tr>
            <td>Nr konta:</td>
            <td>{{ $data['bank_account']['account_number'] }}</td>
        </tr>
        <tr>
            <td>Nazwa banku:</td>
            <td>{{ $data['bank_account']['bank_name'] }}</td>
        </tr>
        <tr>
            <td>SWIFT:</td>
            <td>{{ $data['bank_account']['swift'] }}</td>
        </tr>
        @endif
        <tr>
            <td>Autor wiadomości:</td>
            <td>{{ $data['username'] }}</td>
        </tr>
        <tr>
            <td><a href="{{ $data['url'] }}">Profil w systemie lojalnościowym</a></td>
            <td></td>
        </tr>
        <tr>
            <td><a href="{{ $data['url_crm'] }}">Profil w systemie CRM</a></td>
            <td></td>
        </tr>
    </table>


</body>

</html>
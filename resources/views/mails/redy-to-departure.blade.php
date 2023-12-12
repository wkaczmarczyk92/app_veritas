<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data gotowości do wyjazdu</title>
</head>
<style>

.bg-test {
    background-color: red;
}

</style>
<body>
    
    <table>
        <tr>
            <td>Nowa data gotowości do wyjazdu:</td>
            <td>{{ $data['date'] }}</td>
        </tr>
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
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'VeritasApp') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <script src="https://kit.fontawesome.com/7d190e67e0.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="/css/user.css">
    <link rel="stylesheet" href="/css/style.css">

    <link rel="icon" href="/images/favicon.png" sizes="32x32">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" />





    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body class="tw-font-sans tw-antialiased">
    @inertia
    <!-- <script src="../node_modules/flowbite/dist/flowbite.min.js"></script> -->

    <!-- <script src="../path/to/flowbite/dist/flowbite.min.js"></script> -->

    <!-- <script
            type="text/javascript"
            src="../node_modules/tw-elements/dist/js/tw-elements.umd.min.js"></script> -->
</body>

</html>
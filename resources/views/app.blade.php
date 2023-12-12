<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <script src="https://kit.fontawesome.com/d03a4c101e.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="/css/admin.css">
        <link rel="stylesheet" href="/css/user.css">

        <link rel="icon" href="/images/favicon.png" sizes="32x32">
        
        
        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
        <!-- <script src="../node_modules/flowbite/dist/flowbite.min.js"></script> -->

        <!-- <script src="../path/to/flowbite/dist/flowbite.min.js"></script> -->

        <!-- <script
            type="text/javascript"
            src="../node_modules/tw-elements/dist/js/tw-elements.umd.min.js"></script> -->
    </body>
</html>

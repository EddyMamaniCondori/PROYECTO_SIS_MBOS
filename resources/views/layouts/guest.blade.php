<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('MBOS', 'Login') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased"
      style="
        background: url('{{ asset('img/fondo.jpeg') }}') no-repeat center center fixed;
        background-size: cover;
      ">
       <div class="min-h-screen flex flex-col sm:justify-center items-center pt-2 sm:pt-0">

            <div >
                <img src="{{ asset('img/logoiasd.png') }}" class="w-24 drop-shadow-lg" alt="Logo">
                <br>
               
            </div>
            <strong class="mb-4 text-2xl font-extrabold **font-serif**">
                <center><h5>MISION BOLIVIANA OCCIDENTAL SUR</h5></center>
            <div class="mt-20 md:mt-40"></div>
    </strong>
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>

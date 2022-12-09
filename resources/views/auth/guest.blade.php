<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __("layout.title") }}</title>

        <link rel="stylesheet" href="{{ asset('style/auth.css') }}">

        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    </head>
    <body>
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>
        <script src="{{ asset('js/auth.js') }}"></script>
    </body>
</html>

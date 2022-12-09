<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="{{ mix('style/app.css') }}" rel="stylesheet">
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
        <title>{{ __("layout.title") }}</title>
    </head>
    <body>
        <input id="home-url" type="hidden" value="{{ route('index') }}">
        <input id="home-url-part" type="hidden" value="{{ route('partials.person.close') }}">
        <input id="log-url" type="hidden" value="{{ route('log') }}">
        @include("partials.spinner")
        <header class="header" id="header">
            <a class="header-logo"
                href="{{ route('index') }}"
                title="{{ __('layout.title') }}"
                tabindex="0"
                >
                <img class="icon-lg" src="{{ asset('img/layout/logo.svg') }}" alt="{{ __('layout.title') }}">
            </a>
            <div class="menu">
                <button class="button" id="people-dropdown-button" title="{{ __('people.dropdown_button.tooltip') }}" tabindex="0">
                    <img class="icon-lg" src="{{ asset('img/people/dropdown.svg') }}" alt="people-dropdown">
                </button>
                @include("partials.events.button-show")
                @include("download")
                @include("auth.logout")
            </div>
        </header>
        <div class="content">
            <aside class="people-container" id="people-container">
                @yield("people")
            </aside>
            <main class="main-container" id="main-container">
                @yield("main")
            </main>
        </div>
        <footer class="footer" id="footer">
            <a href="https://github.com/mldanshin/danshin_gen"
                target="_blank"
                title="{{ __('layout.github.tooltip') }}"
                > 
                <img class="icon-lg" src="{{ asset('img/layout/github.svg') }}" alt="github">
            </a>
            <div class="about">
                <div class="about-description">
                    <span>{{ __("app.development.role") }}</span>
                    <span>{{ __("app.development.name") }}</span>
                    <span>{{ __("app.year.start") }}-{{ __("app.year.end") }}</span>
                </div>
                <address class="about-email">
                    <a class="about-email-link" href="mailto:{{ __('app.development.email') }}" rel="nofollow">{{ __("app.development.email") }}</a>
                </address>
            </div>
        </footer>
        @include("partials.messages")
        @include("partials.toast")
    </body>
    <script src="{{ mix('js/app.js') }}"></script>
</html>

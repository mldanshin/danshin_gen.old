<x-guest-layout>
    <x-auth-card>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route("login") }}">
            @csrf

            <!-- Identifier -->
            <div>
                <x-label for="identifier" :value="__('auth.identifier')" />
                <x-input class="block mt-1 w-full"
                    id="identifier" 
                    type="text"
                    name="identifier"
                    :value="old('identifier')"
                    required
                    autofocus
                    autocomplete="off"
                    />
                <small>{{ __("auth.identifier_rule") }}</small>
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('auth.password')" />
                <x-input class="block mt-1 w-full" 
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label class="inline-flex items-center" for="remember_me">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __("auth.remember_me") }}</span>
                </label>
            </div>

            <!-- Log in -->
            <div class="flex flex-col items-start mt-4">
                <x-button class="ml-3 mb-3">
                    {{ __("auth.log_in") }}
                </x-button>

                <a class="underline text-sm text-gray-600 hover:text-gray-900 mt-3" href="{{ route('register') }}">
                    {{ __("auth.register") }}
                </a>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>

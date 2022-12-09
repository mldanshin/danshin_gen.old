<x-guest-layout>
    <x-auth-card>
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />
        @if (session('message'))
            <div class="text-red-600">
                {{ session('message') }}
            </div>
        @endif
        <form method="POST" action="{{ route('register.handler') }}">
            @csrf
            <div>
                {{ __("auth.identifier_info") }}
            </div>
            <!-- Identifier -->
            <div class="mt-4">
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
                    minlength="8"
                    autocomplete="new-password" />
                <small>{{ __("auth.password_rule") }}</small>
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('auth.password_confirm')" />
                <x-input class="block mt-1 w-full"
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    minlength="8"
                    required />
            </div>

            <div class="flex flex-row flex-wrap items-center justify-between">
                <x-button class="mt-4">
                    {{ __("auth.register") }}
                </x-button>
                <a class="underline text-sm text-gray-600 hover:text-gray-900 mt-4" href="{{ route('login') }}">
                    {{ __("auth.is_registered") }}
                </a>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>

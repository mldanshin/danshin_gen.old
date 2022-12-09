<?php
/**
 * @var App\Models\Auth\Registration\ConfirmationCode $model
 */
?>
<x-guest-layout>
    <x-auth-card>
        <x-auth-validation-errors class="mb-4" :errors="$errors" />
        @if (session('message'))
            <div class="text-red-600">
                {{ session('message') }}
            </div>
        @endif
        <form method="post" action="{{ route('register.confirmation.handler') }}">
            @csrf
            <input type="hidden" name="id" value="{{ $model->getId() }}">
            <div>
                <label for="input-code">{{ __("auth.confirm.input_code") }}</label>
                <input class="my-3" id="input-code" type="text" name="code" autofocus autocomplete="off">
            </div>
            <x-button class="mb-3">
                {{ __("auth.confirm.confirm") }}
            </x-button>
        </form>
        <div>{{ __("auth.confirm.attempts", ["attempts" => $model->getAttempts()]) }}</div>
        <div>
            <span>{{ __("auth.confirm.time") }}</span>
            <span id="auth-confirmation-time"
                data-href="{{ route('register.confirmation-repeated', [$model->getId()]) }}"
                >
                {{ $model->getTimeStamp() }}
            </span>
            <span>{{ __("auth.confirm.sec") }}</span>
        </div>
        <div class="mt-5">
            @include("auth.time-repeat", ["userId" => $model->getId(), "repeatTimestamp" => $model->getRepeatTimestamp()])
        </div>
    </x-auth-card>
</x-guest-layout>

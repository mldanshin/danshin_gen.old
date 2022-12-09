<?php
/**
 * @var string $userId
 * @var string $repeatTimestamp
 */ 
?>
<div class="hidden" id="auth-confirmation-repeated-time-container">
    <span>
        {{ __("auth.confirm.code_repeated_time") }}
    </span>
    <span id="auth-confirmation-repeated-time">
        {{ $repeatTimestamp }}
    </span>
    <span>{{ __("auth.confirm.sec") }}</span>
</div>
<form method="post" action="{{ route('register.confirmation-repeated.handler') }}">
    @csrf
    <input type="hidden" name="id" value="{{ $userId }}">
    <x-button class="hidden" id="auth-confirmation-repeated-button">
        {{ __('auth.confirm.send_repeated_code') }}
    </x-button>
</form>
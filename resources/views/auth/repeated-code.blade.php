<?php
/**
 * @var string $userId
 * @var string $repeatTimestamp
 */ 
?>
<x-guest-layout>
    <x-auth-card>
        <div class="flex flex-col justify-center items-center">
            @if (session('message'))
                <div class="mb-4 text-center text-red-600">
                    {{ session('message') }}
                </div>
            @endif
            <div class="mb-4 text-center">
                {{ __("auth.confirm.repeated_message") }}
            </div>
            @include("auth.time-repeat", ["userId" => $userId, "repeatTimestamp" => $repeatTimestamp])
        </div>
    </x-auth-card>
</x-guest-layout>

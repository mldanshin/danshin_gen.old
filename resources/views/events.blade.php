<?php
/**
 * @var App\Models\Events\Events $main
 * @var App\Models\Eloquent\User $user
 */
?>
@extends("layout")

@section("people")
    @include("partials.people.index")
@endsection

@section("main")
    @include("partials.events.subscription.control", [
        "user" => $user
    ])
    <x-events :model="$main" />
@endsection
<?php
/**
 * @var string $userId
 * @var string $code
 */
?>
@extends("layout")

@section("people")
    @include("partials.people.index")
@endsection

@section("main")
    @include("partials.events.subscription.create", [
        "userId" => $userId,
        "code" => $code
    ])
@endsection
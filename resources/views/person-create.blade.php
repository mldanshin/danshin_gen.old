@extends("layout")

@section("people")
    @include("partials.people.index")
@endsection

@section("main")
    @include("partials.person.create", ["model" => $main])
@endsection
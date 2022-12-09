<?php
/**
 * @var string $personId
 * @var string|null $parentId
 */
?>
@extends("layout")

@section("people")
    @include("partials.people.index")
@endsection

@section("main")
    <form id="tree-form" method="post" action="{{ route('partials.tree.index') }}">
        <input type="hidden" name="person_id" value="{{ $personId }}">
        @isset ($parentId)
            <input type="hidden" name="parent_id" value="{{ $parentId }}">
        @endisset
    </form>
@endsection
<?php
/**
 * @var Illuminate\Support\Collection $people;
 */
?>
@if ($people->count() > 0)
    <ul>
    @foreach ($people as $person)
        <li>
            @include("partials.person-short", [
                "model" => $person,
                "classButtonShowPerson" => "people__button-show-person",
                "classButtonShowTree" => "people__button-show-tree"
            ])
        </li>
    @endforeach
    </ul>
@else
    <div class="people-not-found">
        {{ __("people.not_found") }}
    </div>
@endif

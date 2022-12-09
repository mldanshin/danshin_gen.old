<?php
/**
 * @var Illuminate\Support\Collection $people;
 * @var App\Models\People\FilterOrdering $filterOrdering
 */
?>
<button class="button people-button-close" id="people-button-close">
    <img class="icon-md" src="{{ asset('img/people/close.svg')}}" alt="close">
</button>
<form id="people-form" method="post" action="{{ route('partials.people.filter_ordering') }}">
    @include("partials.people.partials.filter-ordering", ["model" => $filterOrdering])
</form>
<div class="people-collection-container" id="people-collection-container">
    @include("partials.people.partials.list")
</div>
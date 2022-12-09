<?php
/**
 * @var Iterable $iterable
 * @var string $viewName
 */
?>
<div class="list-input-container">
    @foreach ($iterable as $item)
        @include($viewName, ["item" => $item])
    @endforeach
    <button class="button-add"
        type="button"
        title="{{ __('person.crud.list_input.add') }}"
        data-href-part="{{ route("partials.person.list_input", $viewName) }}"
        >
        <img class="icon-sm" src="{{ asset('img/person/add.svg') }}" alt="add">
    </button>
</div>
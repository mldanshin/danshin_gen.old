<?php
/**
 * @var int $personId
 */
?>
<form id="person-destroy-form" method="post" action="{{ route('partials.person.destroy', $personId) }}" >
    @method("delete")
    <input class="button icon-lg"
        id="person-destroy-button"
        type="image"
        src="{{ asset('img/person/crud/delete.svg') }}"
        alt="delete"
        title="{{ __('person.crud.destroy') }}"
        data-href="{{ route("index") }}">
</form>
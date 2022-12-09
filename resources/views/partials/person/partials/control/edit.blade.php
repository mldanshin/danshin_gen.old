<?php
/**
 * @var int $personId
 */
?>
<button class="button"
    id="person-edit-button"
    type="button"
    data-href="{{ route('person.edit', $personId) }}"
    data-href-part="{{ route('partials.person.edit', $personId) }}"
    title="{{ __('person.crud.edit') }}"
    tabindex="0"
    >
    <img class="icon-lg" src="{{ asset('img/person/crud/edit.svg') }}" alt="edit">
</button>
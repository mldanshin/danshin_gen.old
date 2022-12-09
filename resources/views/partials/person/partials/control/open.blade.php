<?php
/**
 * @var int $personId
 */
?>
<button class="button person__button-show-person"
    id="person-show-button"
    type="button"
    title="{{ __('person.crud.show') }}"
    data-href="{{ route('person.show', $personId) }}"
    data-href-part="{{ route('partials.person.show', $personId) }}"
    tabindex="0"
    >
    <img class="icon-lg" src="{{ asset('img/person/card.svg') }}" alt="card">
</button>
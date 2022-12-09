<button class="button"
    id="person-create-button"
    type="button"
    data-href="{{ route('person.create') }}"
    data-href-part="{{ route('partials.person.create') }}"
    title="{{ __('person.crud.create') }}"
    tabindex="0"
    >
    <img class="icon-lg" src="{{ asset('img/person/crud/create.svg') }}" alt="create">
</button>
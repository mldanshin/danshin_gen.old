<?php
/**
 * @var App\Models\Person\Editable\Form\Parents $parents
 */
?>
<div id="person-parents">
    @foreach ($parents->getParent() as $item)
        @include("partials.person.partials.editor.parent", ["item" => $item])
    @endforeach
    <div class="add-selected">
        <select id="person-parents-type-sample">
            @foreach ($parents->getRoleOptions() as $option)
            <option value="{{ $option->getId() }}">
                {{ $option->getName() }}
            </option>
            @endforeach
        </select>
        <button class="button-add"
            id="person-parents-add"
            type="button"
            title="{{ __('person.crud.list_input.add') }}"
            data-href-part="{{ route("partials.person.parent") }}"
            >
            <img class="icon-sm" src="{{ asset('img/person/add.svg') }}" alt="add">
        </button>
    </div>
</div>
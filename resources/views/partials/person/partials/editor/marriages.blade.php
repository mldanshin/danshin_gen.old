<?php
/**
 * @var App\Models\Person\Editable\Form\Marriages $marriages
 */
?>
<div id="person-marriages">
    @foreach ($marriages->getMarriage() as $item)
        @include("partials.person.partials.editor.marriage", ["item" => $item])
    @endforeach
    <div class="add-selected">
        <select id="person-marriages-type-sample">
            @foreach ($marriages->getRoleOptions() as $option)
            <option value="{{ $option->getId() }}">
                {{ $option->getName() }}
            </option>
            @endforeach
        </select>
        <button class="button-add"
            id="person-marriages-add"
            type="button"
            title="{{ __('person.crud.list_input.add') }}"
            data-href-part="{{ route("partials.person.marriage") }}"
            >
            <img class="icon-sm" src="{{ asset('img/person/add.svg') }}" alt="add">
        </button>
    </div>
</div>
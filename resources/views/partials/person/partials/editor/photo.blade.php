<?php
/**
 * @var App\Models\Person\Editable\Form\Photo|null $item
 */
$id = Str::uuid();
$date = (isset($item)) ? $item->getDate() : null;
?>
<div class="content-container" id="{{ $id }}">
    <div>
        <img class="photo-img" src="{{ $item->getUrl() }}" alt="photo">
        <div>{{ __("person.photo.date.label") }}</div>
        @include("partials.person.partials.editor.date", ["name" => "person_photo[$id][date]", "date" => $date])
        <div>{{ __("person.photo.order.label") }}</div>
        <input class="number-input" type="number" name="person_photo[{{ $id }}][order]" value="{{ $item->getOrder() }}" min="1" required>
        <small class="help-rule">{{ __("person.photo.order.rule") }}</small>
        <input type="hidden" name="person_photo[{{ $id }}][url]" value="{{ $item->getUrl() }}" required>
        <input type="hidden" name="person_photo[{{ $id }}][path_relative]" value="{{ $item->getPathRelative() }}" required>
    </div>
    <button class="button-add" type="button" title="{{ __('person.crud.list_input.del') }}" data-type="button-delete" data-id="{{ $id }}">
        <img class="icon-sm" src="{{ asset('img/person/delete-item.svg') }}" alt="delete-item" data-type="button-delete">
    </button>
</div>
<?php
/**
 * @var App\Models\Person\Editable\OldSurname|null $item
 */
$id = Str::uuid();
?>
<div class="content-container">
    <div>
        <div>{{ __("person.old_surname.name.label") }}</div>
        <input class="field-text" 
            type="text"
            name="person_old_surnames[{{ $id }}][name]"
            @isset($item) value="{{ $item->getSurname() }}" @endisset
            required>
        <div>{{ __("person.old_surname.order.label") }}</div>
        <div>
            <input class="number-input"
                type="number"
                name="person_old_surnames[{{ $id }}][order]"
                @isset($item) value="{{ $item->getOrder() }}" @endisset
                required>
            <small class="help-rule">{{ __("person.old_surname.order.help") }}</small>
            <small class="help-rule">{{ __("person.old_surname.order.rule") }}</small>
        </div>
    </div>
    @include("partials.person.partials.editor.button-delete-item")
</div>
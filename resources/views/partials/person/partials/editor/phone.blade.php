<?php
/**
 * @var string|null $item
 */
?>
<div class="content-container">
    <div>
        <input class="tel-input"
            type="tel"
            name="person_phones[]"
            maxlength="20"
            @isset($item) value="{{ $item }}" @endisset
            required
            pattern="[0-9]+">
        <small class="help-rule">{{ __('person.phones.rule') }}</small>
    </div>
    @include("partials.person.partials.editor.button-delete-item")
</div>
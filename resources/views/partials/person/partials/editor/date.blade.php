<?php
/**
 * @var string $name
 * @var string|null $date
 */
?>
<div>
    <input class="date-input"
        type="text"
        name="{{ $name }}"
        @empty(!$date) value="{{ $date }}" @endempty
        maxlength="10"
        pattern="[0-9\?]{4}-([0\?]{1}[1-9\?]{1}|[1\?]{1}[012\?]{1})-([0-2\?]{1}[0-9\?]{1}|[3\?]{1}[01\?]{1})">
    <small class="help-rule">{{ __("person.date.rule") }}</small>
</div>
<?php
/**
 * @var string|null $item
 */
?>
<div class="content-container">
    <input type="email" name="person_emails[]" @isset($item) value="{{ $item }}" @endisset required>
    @include("partials.person.partials.editor.button-delete-item")
</div>
<?php
/**
 * @var string|null $item
 */
?>
<div class="content-container">
    <textarea name="person_activities[]" required>@isset($item){{ $item }}@endisset</textarea>
    @include("partials.person.partials.editor.button-delete-item")
</div>
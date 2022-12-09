<?php
/**
 * @var App\Models\Person\Editable\Residence|null $item
 */
$id = Str::uuid();
$date = (isset($item)) ? $item->getDate() : null;
?>
<div class="content-container">
    <div>
        <div>{{ __("person.residences.name.label") }}</div>
        <textarea class="field-text" name="person_residences[{{ $id }}][name]" required>@isset($item){{ $item->getName() }}@endisset</textarea>
        <div>{{ __("person.residences.date.label") }}</div>
        @include("partials.person.partials.editor.date", ["name" => "person_residences[$id][date]", "date" => $date])
    </div>
    @include("partials.person.partials.editor.button-delete-item")
</div>
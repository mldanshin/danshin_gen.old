<?php
/**
 * @var App\Models\Person\Editable\Internet|null $item
 */
$id = Str::uuid();
?>
<div class="content-container">
    <div>
        <div>{{ __("person.internet.name.label") }}</div>
        <textarea class="field-text" name="person_internet[{{ $id }}][name]" required placeholder="{{ __('person.internet.name.help') }}">@isset($item){{ $item->getName() }}@endisset</textarea>
        <div>{{ __("person.internet.url.label") }}</div>
        <input class="field-text"
            type="url"
            name="person_internet[{{ $id }}][url]"
            @isset($item) value="{{ $item->getUrl() }}" @endisset
            required
            placeholder="{{ __('person.internet.url.help') }}">
    </div>
    @include("partials.person.partials.editor.button-delete-item")
</div>
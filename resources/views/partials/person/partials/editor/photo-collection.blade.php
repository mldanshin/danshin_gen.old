<?php
/**
 * @var Illuminate\Support\Collection|App\Models\Person\Editable\Form\Photo $photo
 */
?>
<div id="person-photo-list">
    @foreach ($photo as $item)
        @include("partials.person.partials.editor.photo", ["item" => $item])
    @endforeach
    <div>
        <label for="person-photo-add">{{ __("person.photo.adding") }}</label>
        <input id="person-photo-add" 
            type="file"
            name="person_photo_file"
            accept=".jpg, .jpeg, .png, .WebP, .bmp, .gif, .svg"
            data-href-part="{{ route("partials.person.photo") }}">
    </div>
</div>
<?php
/**
 * @var App\Models\Person\Editable\Form\Person $model
 */
?>
@can("editor")
    <div class="person-control">
        @include("partials.person.partials.control.create")
        @include("partials.person.partials.control.store")
    </div>
@endcan
<div class="person-container">
    @include("partials.person.partials.close")
    @include("partials.person.partials.editor.person", [
        "model" => $model,
        "formActionUrl" => route('partials.person.store'),
        "method" => "POST"
    ])
</div>

<?php
/**
 * @var App\Models\Person\Editable\Form\Person $model
 */
?>
<div class="person-control">
    @can("editor")
        @include("partials.person.partials.control.create")
        @include("partials.person.partials.control.update")
        @include("partials.person.partials.control.destroy", ["personId" => $model->getId()])
    @endcan
    @include("partials.person.partials.control.open", ["personId" => $model->getId()])
</div>
@if ($errors)
<div class="alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="person-container">
    @include("partials.person.partials.close")
    @include("partials.person.partials.editor.person", [
        "model" => $model,
        "formActionUrl" => route('partials.person.update', $model->getId()),
        "method" => "PUT"
    ])
</div>

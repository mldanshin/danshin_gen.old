<?php
/**
 * @var App\Models\Person\Readable\Person $model;
 */
?>
<div class="person-control">
    @can("editor")
        @include("partials.person.partials.control.create")
        @include("partials.person.partials.control.edit", ["personId" => $model->getId()])
        @include("partials.person.partials.control.destroy", ["personId" => $model->getId()])
    @endcan
    @include("partials.person.partials.control.tree", ["personId" => $model->getId()])
    @include("partials.person.partials.control.download", ["personId" => $model->getId()])
</div>
<div class="person-container">
    @include("partials.person.partials.close")
    @include("partials.person.partials.reader.person", ["model" => $model])
</div>

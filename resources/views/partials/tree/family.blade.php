<?php
/**
 * @var App\Models\Tree\Family $family
 */
?>
@include("partials.tree.person", ["person" => $family->getPerson()])
@foreach ($family->getMarriage() as $item)
    @include("partials.tree.person", ["person" => $item])
@endforeach
@foreach ($family->getChildrens() as $item)
    @include("partials.tree.family", [
        "family" => $item
    ])
@endforeach
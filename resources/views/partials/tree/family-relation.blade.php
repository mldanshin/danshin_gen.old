<?php
/**
 * @var App\Models\Tree\Family $family
 */
?>
@includeWhen(
    $family->getParentRelation(),
    "partials.tree.parent-children-relation",
    ["relation" => $family->getParentRelation()]
)
@foreach ($family->getChildrens() as $item)
    @include("partials.tree.family-relation", [
        "family" => $item
    ])
@endforeach
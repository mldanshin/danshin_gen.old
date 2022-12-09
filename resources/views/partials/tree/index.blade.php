<?php
/**
 *@var App\Models\Tree\Toggle|null $toggle
 *@var App\View\Tree\Tree $tree
 */
?>
<h2 class="tree-title">
    <span>
        {{ __("tree.title") }}
    </span>
    <span>
        {{ PersonHelper::surname($tree->getPersonTarget()->getSurname()) }}
        {{ PersonHelper::name($tree->getPersonTarget()->getName()) }}
        {{ PersonHelper::patronymic($tree->getPersonTarget()->getPatronymic()) }}
    </span>
</h2>
@include("partials.tree.control", [
    "toggle" => $toggle,
    "personTargetId" => $tree->getPersonTarget()->getId(),
    "personParentId" => ($toggle === null) ? null : $toggle->getActive()
    ])
<div class="tree-object-container" id="tree-object-container">
    @include("partials.tree.tree", ["tree" => $tree])
</div>
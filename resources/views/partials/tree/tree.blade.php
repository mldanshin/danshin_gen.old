<?php
/**
 *@var App\View\Tree\Tree $tree
 */
?>
<svg xmlns="http://www.w3.org/2000/svg"
    width="{{ $tree->getSize()->getWidth() }}"
    height="{{ $tree->getSize()->getHeight() }}"
    >
    <style title="tree-style-svg">
        .tree-person {
            fill: white;
            stroke:#3b3a3a;
            stroke-width: {{ $tree->getStylePerson()->getStrokeWidth() }};
        }
        .tree-person-basic {
            fill: #7B68EE;
            stroke:#3b3a3a;
        }
        .tree-font {
            font-size: {{ $tree->getStylePerson()->getFontSize() }}px;
            text-anchor: middle;
        }
        .parent-children-relation {
            stroke: #3b3a3a;
            stroke-width: 1;
        }
    </style>
    @include("partials.tree.family-relation", [
        "family" => $tree->getFamily()
    ])
    @include("partials.tree.family", [
        "family" => $tree->getFamily()
    ])
    <g id="marker-adding"></g>
</svg>
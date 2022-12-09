<?php
/**
 * @var App\View\Tree\ParentChildrenRelation $relation
 */
?>
<line class="parent-children-relation"
    x1="{{ $relation->getPoint1()->getX() }}"
    y1="{{ $relation->getPoint1()->getY() }}"
    x2="{{ $relation->getPoint2()->getX() }}"
    y2="{{ $relation->getPoint2()->getY() }}"
    />
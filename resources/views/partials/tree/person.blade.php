<?php
/**
 * @var App\View\Tree\Person $person;
 */
?>
<g class="tree-person-container">
    <rect class="@if ($person->isPersonTarget()) {{ " tree-person-basic " }} @else {{ " tree-person " }} @endif"
        @if ($person->isPersonTarget()) {!! "id=\"tree-person-basic\"" !!} @else {{ "" }} @endif
        x="{{ $person->getPoint()->getX() }}"
        y="{{ $person->getPoint()->getY() }}"
        width="{{ $person->getSize()->getWidth() }}"
        height="{{ $person->getSize()->getHeight() }}"
        />
    @include("partials.tree.text", ["text" => $person->getSurname()])
    @includeWhen($person->getOldSurname(), "partials.tree.text", ["text" => $person->getOldSurname()])
    @include("partials.tree.text", ["text" => $person->getName()])
    @include("partials.tree.text", ["text" => $person->getPatronymic()])
    @include("partials.tree.text", ["text" => $person->getPeriodLive()])
    @empty(!$person->getLinkCard())
        @include("partials.tree.link", ["link" => $person->getLinkCard(), "class" => "tree__button-show-person"])
    @endempty
    @empty(!$person->getLinkTree())
        @include("partials.tree.link", ["link" => $person->getLinkTree(), "class" => "tree__button-show-tree"])
    @endempty
</g>
<?php
/**
 * @var App\View\Tree\Text $text
 */
?>
<text class="tree-person-text tree-font"
    x="{{ $text->getPoint()->getX() }}"
    y="{{ $text->getPoint()->getY() }}">
    {{ $text->getContent() }}
</text>
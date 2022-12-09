<?php
/**
 * @var App\View\Tree\Link $link
 * @var string $class
 */
?>
<image class="button icon-sm {{ $class }}"
    href="{{ $link->getImageHref() }}"
    x="{{ $link->getPoint()->getX() }}"
    y="{{ $link->getPoint()->getY() }}"
    width="{{ $link->getSize()->getWidth() }}"
    height="{{ $link->getSize()->getWidth() }}"
    data-person="{{ $link->getPersonId() }}"
    data-href="{{ $link->getHref() }}"
    data-href-part="{{ $link->getHrefPart() }}"
/>
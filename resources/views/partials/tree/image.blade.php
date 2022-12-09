<?php
/**
 * @var App\View\Tree\Image $image
 */
?>
<image href="{{ $image->getHref() }}"
    x="{{ $image->getPoint()->getX() }}"
    y="{{ $image->getPoint()->getY() }}"
    width="{{ $image->getSize()->getWidth() }}"
    height="{{ $image->getSize()->getWidth() }}"
    />
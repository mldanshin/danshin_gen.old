<?php

namespace App\View\Tree;

final class Image extends Element
{
    public function __construct(
        int $width,
        int $height,
        private string $href
    ) {
        $this->size = new Size($width, $height);
    }

    public function setPoint(int $x, int $y): void
    {
        $this->point = new PointXY($x, $y);
    }

    public function getHref(): string
    {
        return $this->href;
    }
}

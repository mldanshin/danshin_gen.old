<?php

namespace App\View\Tree;

final class Text
{
    public function __construct(
        private string $content,
        private ?PointXY $point = null,
    ) {
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getPoint(): ?PointXY
    {
        return $this->point;
    }

    public function setPoint(int $x, int $y): void
    {
        $this->point = new PointXY($x, $y);
    }
}

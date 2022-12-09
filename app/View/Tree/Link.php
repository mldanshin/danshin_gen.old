<?php

namespace App\View\Tree;

final class Link
{
    public function __construct(
        private string $personId,
        private string $href,
        private string $hrefPart,
        private string $imageHref,
        private Size $size,
        private ?PointXY $point = null,
    ) {
    }

    public function getPersonId(): string
    {
        return $this->personId;
    }

    public function getHref(): string
    {
        return $this->href;
    }

    public function getHrefPart(): string
    {
        return $this->hrefPart;
    }

    public function getImageHref(): string
    {
        return $this->imageHref;
    }

    public function getSize(): Size
    {
        return $this->size;
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

<?php

namespace App\View\Tree;

abstract class Element
{
    protected Size $size;
    protected PointXY $point;

    public function getSize(): Size
    {
        return $this->size;
    }

    public function getPoint(): PointXY
    {
        return $this->point;
    }

    abstract public function setPoint(int $x, int $y): void;

    protected function compareWidthElement(?Element $element1, Element $element2): Element
    {
        if ($element1 === null) {
            return $element2;
        }

        if ($element1->getSize()->getWidth() > $element2->getSize()->getWidth()) {
            return $element1;
        } else {
            return $element2;
        }
    }

    protected function compareHeightElement(?Element $element1, Element $element2): Element
    {
        if ($element1 === null) {
            return $element2;
        }

        if ($element1->getSize()->getHeight() > $element2->getSize()->getHeight()) {
            return $element1;
        } else {
            return $element2;
        }
    }
}

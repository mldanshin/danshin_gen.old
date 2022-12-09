<?php

namespace App\View\Tree;

final class ParentChildrenRelation
{
    public function __construct(
        private PointXY $point1,
        private PointXY $point2
    ) {
    }

    public function getPoint1(): PointXY
    {
        return $this->point1;
    }

    public function getPoint2(): PointXY
    {
        return $this->point2;
    }
}

<?php

namespace App\View\Tree;

final class StylePerson
{
    public function __construct(
        private int $margine,
        private float $strokeWidth,
        private int $padding,
        private int $fontSize,
        private int $lineSpacing,
        private Size $button
    ) {
    }

    public function getMargine(): int
    {
        return $this->margine;
    }

    public function getStrokeWidth(): float
    {
        return $this->strokeWidth;
    }

    public function getPadding(): int
    {
        return $this->padding;
    }

    public function getFontSize(): int
    {
        return $this->fontSize;
    }

    public function getLineSpacing(): int
    {
        return $this->lineSpacing;
    }

    public function getButton(): Size
    {
        return $this->button;
    }
}

<?php

namespace App\View\Tree;

use App\Models\Tree\Family as FamilyModel;
use App\Models\Tree\Person as PersonModel;
use Illuminate\Support\Collection;

final class Family extends Element
{
    private Person $person;
    /**
     * @var Collection|Person[] $marriage
     */
    private Collection $marriage;
    /**
     * @var Collection|Family[] $childrens
     */
    private Collection $childrens;
    private ?ParentChildrenRelation $parentRelation = null;
    private int $margine;

    public function __construct(FamilyModel $family, private StylePerson $style, private bool $hasLinks)
    {
        $this->margine = $this->style->getMargine();

        $this->person = new Person($family->getPerson(), $this->style, $this->hasLinks);
        $this->initializeMarriage($family->getMarriage());
        $this->initializeChildrens($family->getChildrens());

        $this->initializeSize();
    }

    public function getPerson(): Person
    {
        return $this->person;
    }

    /**
     * @return Collection|Person[]
     */
    public function getMarriage(): Collection
    {
        return $this->marriage;
    }

    /**
     * @return Collection|Family[]
     */
    public function getChildrens(): Collection
    {
        return $this->childrens;
    }

    public function getParentRelation(): ?ParentChildrenRelation
    {
        return $this->parentRelation;
    }

    public function setPointWrapper(int $x, int $y, PointXY $parent): void
    {
        $this->setPoint($x, $y);
        $this->parentRelation = new ParentChildrenRelation(
            $parent,
            new PointXY(
                $this->person->getPoint()->getX() + ($this->person->getSize()->getWidth() / 2),
                $this->person->getPoint()->getY()
            )
        );
    }

    public function setPoint(int $x, int $y): void
    {
        $this->point = new PointXY($x, $y);

        $middleWidthFamily = $x + ($this->size->getWidth() / 2);

        $y = $this->setPointPerson($middleWidthFamily, $y);
        $y = $this->setPointMarriage($middleWidthFamily, $y);
        $this->setPointChildren($x, $y);
    }

    /**
     * @param Collection|PersonModel[] $people
     */
    private function initializeMarriage(Collection $people): void
    {
        $this->marriage = collect();
        foreach ($people as $person) {
            $this->marriage->add(new Person($person, $this->style, $this->hasLinks));
        }
    }

    /**
     * @param Collection|FamilyModel[] $childrens
     */
    private function initializeChildrens(Collection $childrens): void
    {
        $this->childrens = collect();

        foreach ($childrens as $children) {
            $this->childrens->add(new Family($children, $this->style, $this->hasLinks));
        }
    }

    private function initializeSize(): void
    {
        $marriage = $this->getSizeMarriage();
        $childrens = $this->getSizeChildrens($this->childrens);

        $widthMax = max(
            $this->person->getSize()->getWidth() + ($this->margine * 2),
            $marriage->getWidth(),
            $childrens->getWidth(),
        );

        $this->size = new Size(
            $widthMax,
            ($this->person->getSize()->getHeight() + ($this->margine * 2))
                + $marriage->getHeight()
                + $childrens->getHeight()
        );
    }

    private function getSizeMarriage(): Size
    {
        if ($this->marriage->isEmpty()) {
            return new Size(0, 0);
        }

        $widthMaxElement = null;
        $height = 0;
        $width = 0;

        foreach ($this->marriage as $marriage) {
            $widthMaxElement = $this->compareWidthElement($widthMaxElement, $marriage);
            $height += $marriage->getSize()->getHeight() + ($this->margine * 2);
        }

        if ($widthMaxElement !== null) {
            $width = $widthMaxElement->getSize()->getWidth();
        }

        return new Size($width + ($this->margine * 2), $height);
    }

    /**
     * @param Collection|Family[] $families
     */
    private function getSizeChildrens(Collection $families): Size
    {
        if ($families->isEmpty()) {
            return new Size(0, 0);
        }

        $width = 0;
        $height = 0;

        $heightMaxElement = null;
        foreach ($families as $family) {
            $heightMaxElement = $this->compareHeightElement($heightMaxElement, $family);
            $width = $width + $family->getSize()->getWidth() + ($this->margine * 2);
        }
        if ($heightMaxElement !== null) {
            $height = $heightMaxElement->getSize()->getHeight();
        }

        return new Size($width, $height + ($this->margine * 2));
    }

    private function setPointPerson(int $middleWidthFamily, int $y): int
    {
        $halfPerson = $this->person->getSize()->getWidth() / 2;
        $this->person->setPoint(
            $middleWidthFamily - $halfPerson,
            $y + $this->margine
        );
        return $y + $this->person->getSize()->getHeight() + $this->margine;
    }

    private function setPointMarriage(int $middleWidthFamily, int $y): int
    {
        foreach ($this->marriage as $marriage) {
            $halfPerson = $marriage->getSize()->getWidth() / 2;
            $marriage->setPoint(
                $middleWidthFamily - $halfPerson,
                $y + $this->margine
            );
            $y += $marriage->getSize()->getHeight() + $this->margine;
        }

        return $y;
    }

    private function setPointChildren(int $x, int $y): void
    {
        $parentWidth = $this->getSize()->getWidth();
        $childrensWidth = 0;
        foreach ($this->childrens as $children) {
            $childrensWidth += $children->getSize()->getWidth();
        }
        $span = ($parentWidth - $childrensWidth) / ($this->childrens->count() + 1);

        foreach ($this->childrens as $children) {
            $children->setPointWrapper(
                $x + $span,
                $y,
                new PointXY(
                    $this->person->getPoint()->getX() + ($this->person->getSize()->getWidth() / 2),
                    $this->person->getPoint()->getY() + $this->person->getSize()->getHeight()
                )
            );
            $x += $children->getSize()->getWidth();
        }
    }
}

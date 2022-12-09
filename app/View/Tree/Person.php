<?php

namespace App\View\Tree;

use App\Helpers\Date as DateHelper;
use App\Helpers\Person as PersonHelper;
use App\Models\Tree\Person as PersonModel;

final class Person extends Element
{
    private int $fontSize;
    private int $lineSpacing;
    private int $padding;
    private ?Size $linkSize;
    private bool $isPersonTargetProperty;
    private string $id;
    private Text $surname;
    private ?Text $oldSurname;
    private Text $name;
    private Text $patronymic;
    private Text $periodLive;
    private int $lineHeight;
    private ?Link $linkCard = null;
    private ?Link $linkTree = null;

    public function __construct(PersonModel $model, StylePerson $style, bool $hasLinks)
    {
        $this->fontSize = $style->getFontSize();
        $this->lineSpacing = $style->getLineSpacing();
        $this->padding = $style->getPadding();
        $this->linkSize = ($hasLinks === true) ? $style->getButton() : null;
        $this->lineHeight = $this->fontSize + $this->lineSpacing;
        $this->id = (string)$model->getId();
        $this->isPersonTargetProperty = $model->isPersonTarget();

        $surname = PersonHelper::surname($model->getSurname());
        $oldSurname = ($model->getOldSurname() === null) ? null : PersonHelper::oldSurname($model->getOldSurname());
        $name = PersonHelper::name($model->getName());
        $patronymic = PersonHelper::patronymic($model->getPatronymic());
        $periodLive = DateHelper::periodLive($model->getBirthDate(), $model->getDeathDate());

        $max = 0;
        $max = $this->getLongLengthString($surname, 0);
        $max = $this->getLongLengthString($oldSurname, $max);
        $max = $this->getLongLengthString($name, $max);
        $max = $this->getLongLengthString($patronymic, $max);
        $max = $this->getLongLengthString($periodLive, $max);

        $width = $max * $this->fontSize / 1.5;
        if ($this->linkSize !== null) {
            $widthLinks = ($this->linkSize->getWidth() * 2) + ($this->fontSize * 2);
            if ($widthLinks > $width) {
                $width = $widthLinks;
            }
        }

        $height = ($this->lineHeight * 4)
            + ($this->padding * 2)
            + (($this->linkSize === null) ? 0 : $this->linkSize->getHeight())
            + $this->lineSpacing;

        $this->size = new Size(
            $width + ($this->padding * 2),
            ($oldSurname === null) ? $height : ($height + $this->lineHeight)
        );

        $this->surname = new Text($surname);
        $this->oldSurname = ($oldSurname === null) ? null : new Text($oldSurname);
        $this->name = new Text($name);
        $this->patronymic = new Text($patronymic);
        $this->periodLive = new Text($periodLive);

        if ($hasLinks) {
            $this->initializeLinks();
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSurname(): Text
    {
        return $this->surname;
    }

    public function getOldSurname(): ?Text
    {
        return $this->oldSurname;
    }

    public function getName(): Text
    {
        return $this->name;
    }

    public function getPatronymic(): Text
    {
        return $this->patronymic;
    }

    public function getPeriodLive(): Text
    {
        return $this->periodLive;
    }

    public function isPersonTarget(): bool
    {
        return $this->isPersonTargetProperty;
    }

    public function getLinkCard(): ?Link
    {
        return $this->linkCard;
    }

    public function getLinkTree(): ?Link
    {
        return $this->linkTree;
    }

    public function setPoint(int $x, int $y): void
    {
        $this->point = new PointXY($x, $y);

        $x = $x + ($this->size->getWidth() / 2);
        $y = $y + $this->padding + $this->fontSize;

        $this->surname->setPoint($x, $y);
        $this->oldSurname?->setPoint($x, $y += $this->lineHeight);
        $this->name->setPoint($x, $y += $this->lineHeight);
        $this->patronymic->setPoint($x, $y += $this->lineHeight);
        $this->periodLive->setPoint($x, $y += $this->lineHeight);

        $y += $this->lineSpacing;

        if ($this->linkCard !== null) {
            $this->linkCard->setPoint(
                $x - $this->linkSize->getWidth() - $this->fontSize,
                $y
            );
        }

        if ($this->linkTree) {
            $this->linkTree->setPoint(
                $x + $this->fontSize,
                $y
            );
        }
    }

    private function getLongLengthString(?string $string, int $maxSize): int
    {
        if ($string === null) {
            return $maxSize;
        }

        $length = strlen($string);
        if ($length > $maxSize) {
            return $length;
        } else {
            return $maxSize;
        }
    }

    private function initializeLinks(): void
    {
        $this->linkCard = new Link(
            $this->id,
            route("person.show", $this->id),
            route("partials.person.show", $this->id),
            asset("img/person/card.svg"),
            $this->linkSize
        );

        $this->linkTree = new Link(
            $this->id,
            route("tree", $this->id),
            route("partials.tree.index"),
            asset("img/tree/tree.svg"),
            $this->linkSize
        );
    }
}

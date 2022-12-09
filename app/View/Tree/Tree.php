<?php

namespace App\View\Tree;

use App\Models\Tree\PersonShort;
use App\Models\Tree\Family as FamilyModel;
use App\Models\Tree\Tree as TreeModel;

final class Tree
{
    private StylePerson $stylePerson;
    private PersonShort $personTarget;
    private Family $family;
    private Size $size;

    public function __construct(
        private TreeModel $treeModel,
        private ?int $widthScreen = null,
        private ?int $heightScreen = null,
        bool $hasLinks = true
    ) {
        $this->personTarget = $treeModel->getPersonTarget();

        $this->initializeStylePerson(
            $widthScreen,
            $this->getLargeSize($treeModel->getFamily(), $hasLinks),
        );

        $this->family = new Family($treeModel->getFamily(), $this->stylePerson, $hasLinks);
        $this->family->setPoint(0, 0);

        $this->size = $this->family->getSize();
    }

    public function getPersonTarget(): PersonShort
    {
        return $this->personTarget;
    }

    public function getFamily(): Family
    {
        return $this->family;
    }

    public function getSize(): Size
    {
        return $this->size;
    }

    public function getStylePerson(): StylePerson
    {
        return $this->stylePerson;
    }

    private function initializeStylePerson(?int $widthScreen, Size $preparatorySize): void
    {
        switch ($widthScreen) {
            case ($preparatorySize->getWidth() <= $widthScreen):
                $this->stylePerson = $this->getStylePersonLarge();
                break;
            case ($preparatorySize->getWidth() > ($widthScreen * 2)):
                $this->stylePerson = $this->getStylePersonSmall();
                break;
            case ($preparatorySize->getWidth() > $widthScreen):
                $this->stylePerson = $this->getStylePersonMiddle();
                break;
            default:
                $this->stylePerson = $this->getStylePersonLarge();
                break;
        }
    }

    private function getLargeSize(FamilyModel $model, bool $hasLinks): Size
    {
        return (new Family($model, $this->getStylePersonLarge(), $hasLinks))->getSize();
    }

    private function getStylePersonLarge(): StylePerson
    {
        return new StylePerson(
            config("app.tree.style.person_lg.margine"),
            config("app.tree.style.person_lg.stroke_width"),
            config("app.tree.style.person_lg.padding"),
            config("app.tree.style.person_lg.font_size"),
            config("app.tree.style.person_lg.line_spacing"),
            new Size(
                config("app.tree.style.person_lg.button_width"),
                config("app.tree.style.person_lg.button_height")
            )
        );
    }

    private function getStylePersonMiddle(): StylePerson
    {
        return new StylePerson(
            config("app.tree.style.person_md.margine"),
            config("app.tree.style.person_md.stroke_width"),
            config("app.tree.style.person_md.padding"),
            config("app.tree.style.person_md.font_size"),
            config("app.tree.style.person_md.line_spacing"),
            new Size(
                config("app.tree.style.person_md.button_width"),
                config("app.tree.style.person_md.button_height")
            )
        );
    }

    private function getStylePersonSmall(): StylePerson
    {
        return new StylePerson(
            config("app.tree.style.person_sm.margine"),
            config("app.tree.style.person_sm.stroke_width"),
            config("app.tree.style.person_sm.padding"),
            config("app.tree.style.person_sm.font_size"),
            config("app.tree.style.person_sm.line_spacing"),
            new Size(
                config("app.tree.style.person_sm.button_width"),
                config("app.tree.style.person_sm.button_height")
            )
        );
    }
}

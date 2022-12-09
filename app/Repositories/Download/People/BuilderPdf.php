<?php

namespace App\Repositories\Download\People;

use App\Helpers\Date as DateHelper;
use App\Helpers\Person as PersonHelper;
use App\Models\PersonShort as PersonShortModel;
use App\Models\Person\Readable\Person as PersonModel;
use App\Models\Person\Readable\Photo as PhotoModel;
use App\Repositories\Download\People\FileSystem;
use App\Repositories\Person\Readable\Person as PersonRepository;
use Illuminate\Support\Collection;
use FPDF\FPDF;

final class BuilderPdf extends BuilderAbstract
{
    private FPDF $pdf;
    private int $heightCell;
    private int $leftMarginPage;

    public function __construct(FileSystem $fileSystem, PersonRepository $personRepository)
    {
        parent::__construct($fileSystem, $personRepository);
    }

    public function getPeoplePath(): string
    {
        $func = function () {
            $people = $this->getPeople();
            foreach ($people as $person) {
                $this->createCard($person);
            }
        };

        $path = $this->fileSystem->getPeoplePath("pdf");

        $this->createFile(
            $func,
            $path
        );

        return $path;
    }

    public function getPersonPath(string $id): string
    {
        $func = function () use ($id) {
            $this->createCard(
                $this->getPerson((int)$id)
            );
        };

        $path = $this->fileSystem->getPersonPath($id, "pdf");

        $this->createFile(
            $func,
            $path
        );

        return $path;
    }

    private function createFile(callable $func, string $path): void
    {
        $this->pdf = new FPDF();
        $this->pdf->AddFont("arial", "", "arial.php");
        $this->pdf->AddFont("arial_bold", "", "arial_bold.php");
        $this->heightCell = 5;
        $this->setFontDefault();
        $this->pdf->AddPage();
        $this->leftMarginPage = $this->pdf->GetX();

        $func();

        $this->pdf->Output($path, "F");
    }

    private function createCard(PersonModel $person): void
    {
        $fullName = PersonHelper::surname($person->getSurname()) . " "
            . PersonHelper::name($person->getName()) . " "
            . PersonHelper::patronymic($person->getPatronymic());
        $this->createCellStrong($fullName);

        if ($person->getOldSurname() !== null) {
            $this->createCell(
                __("person.old_surname.label"),
                PersonHelper::oldSurname($person->getOldSurname())
            );
        }

        $this->createCell(
            __("person.gender.label"),
            PersonHelper::gender($person->getGenderId())
        );

        $this->createCell(
            __("person.birth_date.label"),
            $person->getBirthDate()
        );

        $this->createCell(
            __("person.birth_place.label"),
            $person->getBirthPlace()
        );

        if (!$person->isLive()) {
            $this->createCell(
                __("person.death_date.label"),
                $person->getDeathDate()
            );

            $this->createCell(
                __("person.burial_place.label"),
                $person->getBurialPlace()
            );
        }

        if ($person->getNote() !== null) {
            $this->createCell(
                __("person.note.label"),
                $person->getNote()
            );
        }

        if ($person->getActivities() !== null && $person->getActivities()->isNotEmpty()) {
            $this->createCell(
                __("person.activities.label"),
                $person->getActivities()->implode(",")
            );
        }

        if ($person->getEmails() !== null && $person->getEmails()->isNotEmpty()) {
            $this->createCell(
                __("person.emails.label"),
                $person->getEmails()->implode(",")
            );
        }

        if ($person->getInternet() !== null && $person->getInternet()->isNotEmpty()) {
            $this->pdf->Cell(39, $this->heightCell, __("person.internet.label") . ":");
            $person->getInternet()->each(
                function ($item) {
                    $this->pdf->SetTextColor(0, 0, 255);
                    $this->pdf->Write($this->heightCell, $item->getName(), $item->getUrl());
                    $this->pdf->Write($this->heightCell, ", ");
                    $this->pdf->SetTextColor(0, 0, 0);
                }
            );
            $this->pdf->Ln();
        }

        if ($person->getPhones() !== null && $person->getPhones()->isNotEmpty()) {
            $this->createCell(
                __("person.phones.label"),
                $person->getPhones()->implode(",")
            );
        }

        if ($person->getResidences() !== null) {
            $collection = $person->getResidences()->map(
                function ($item) {
                    $str = $item->getName();
                    if (!empty($item->getDate())) {
                        $str .= __(
                            "person.residences.date.content",
                            ["date" => DateHelper::format($item->getDate())]
                        );
                    }
                    return $str;
                }
            );
            $this->createCell(
                __("person.residences.label"),
                $collection->implode(",")
            );
        }

        if ($person->getParents() !== null) {
            $i = 0;
            $person->getParents()->each(
                function ($item) use (&$i) {
                    if ($i === 0) {
                        $this->pdf->Cell(22, $this->heightCell, __("person.parents.label") . ":");
                    } else {
                        $this->pdf->Cell(22, $this->heightCell);
                    }
                    $i++;
                    $this->pdf->Write($this->heightCell, $this->getPersonShort($item->getPerson()));
                    $this->pdf->Ln();
                }
            );
        }

        if ($person->getMarriages() !== null) {
            $i = 0;
            $person->getMarriages()->each(
                function ($item) use (&$i) {
                    if ($i === 0) {
                        $this->pdf->Cell(43, $this->heightCell, __("person.marriages.label") . ":");
                    } else {
                        $this->pdf->Cell(43, $this->heightCell);
                    }
                    $i++;
                    $this->pdf->Write($this->heightCell, $this->getPersonShort($item->getSoulmate()));
                    $this->pdf->Ln();
                }
            );
        }

        if ($person->getChildren() !== null) {
            $i = 0;
            $person->getChildren()->each(
                function ($item) use (&$i) {
                    if ($i === 0) {
                        $this->pdf->Cell(12, $this->heightCell, __("person.children.label") . ":");
                    } else {
                        $this->pdf->Cell(12, $this->heightCell);
                    }
                    $i++;
                    $this->pdf->Write(5, $this->getPersonShort($item));
                    $this->pdf->Ln();
                }
            );
        }

        if ($person->getPhoto() !== null) {
            $this->insertImages($person->getPhoto());
        }

        $this->pdf->Ln();
        $this->pdf->Ln();
    }

    private function createCellStrong(string $value): void
    {
        $this->pdf->SetFont("arial_bold", "", 14);
        $this->pdf->Cell(20, $this->heightCell, $value);
        $this->pdf->Ln();
        $this->setFontDefault();
    }

    private function createCell(string $label, ?string $value): void
    {
        $this->pdf->MultiCell(360, $this->heightCell, $label . ": " . $value, align: "L");
    }

    private function setFontDefault(): void
    {
        $this->pdf->SetFont("arial", "", 12);
    }

    /**
     * @param Collection|PhotoModel[] $photo
     */
    private function insertImages(Collection $photo): void
    {
        $x = 0;
        $y = 0;
        $widthPhoto = 30;
        $maxHeightPhoto = 40;
        $marginBottomPhoto = 5;

        $photo->each(
            function ($item) use (&$x, &$y, $widthPhoto, $maxHeightPhoto, $marginBottomPhoto) {
                if ($x === 0) {
                    $x = $this->pdf->GetX();
                    $y = $this->pdf->GetY();
                } else {
                    $x += $this->pdf->GetX() + $widthPhoto;
                    if (($x + $widthPhoto) > $this->pdf->GetPageWidth()) {
                        $x = $this->leftMarginPage;
                        $y = $this->pdf->GetY() + $maxHeightPhoto + $marginBottomPhoto;
                    } else {
                        $y = $this->pdf->GetY();
                    }
                }

                if ($this->pdf->GetPageHeight() < ($y + $maxHeightPhoto)) {
                    $this->pdf->AddPage();
                    $x = $this->pdf->GetX();
                    $y = $this->pdf->GetY();
                }

                $this->pdf->Image(
                    $item->getPath(),
                    x: $x,
                    y: $y,
                    w: $widthPhoto
                );
            }
        );
        $this->pdf->SetXY(0, $y + 40);
    }

    private function getPersonShort(PersonShortModel $person): string
    {
        return PersonHelper::surname($person->getSurname()) . " "
            . PersonHelper::name($person->getName()) . " "
            . PersonHelper::patronymic($person->getPatronymic());
    }
}

<?php

namespace App\Models\Person\Editable;

use Illuminate\Http\UploadedFile;

final class UploadedPhoto
{
    public function __construct(
        private int $idPerson,
        private UploadedFile $file
    ) {
    }

    public function getIdPerson(): int
    {
        return $this->idPerson;
    }

    public function getFile(): UploadedFile
    {
        return $this->file;
    }
}

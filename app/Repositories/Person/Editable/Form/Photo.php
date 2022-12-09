<?php

namespace App\Repositories\Person\Editable\Form;

use App\Models\Eloquent\Photo as PhotoEloquentModel;
use App\Models\Person\Editable\Photo as PhotoModel;
use App\Models\Person\Editable\UploadedPhoto as UploadedPhotoModel;
use App\Repositories\Person\PhotoFileSystem;
use Illuminate\Support\Collection;

final class Photo
{
    public function __construct(private PhotoFileSystem $fileSystem)
    {
    }
    /**
     * @return Collection|PhotoModel[]
     */
    public function getByPerson(int $personId): Collection
    {
        return PhotoEloquentModel::where("person_id", $personId)->orderBy("_order")->get()
            ->map(
                function ($item) use ($personId) {
                    $path = $this->fileSystem->getPath($personId, $item->file);
                    $this->fileSystem->existsFile($path);
                    return new PhotoModel(
                        $this->fileSystem->getUrl($personId, $item->file),
                        $this->fileSystem->getPathRelative($personId, $item->file),
                        $item->_date,
                        $item->_order,
                    );
                }
            );
    }

    public function upload(UploadedPhotoModel $photo): PhotoModel
    {
        $fileName = $this->fileSystem->putTemp($photo->getFile());
        return new PhotoModel(
            $this->fileSystem->getUrlTemp($fileName),
            $this->fileSystem->getPathTempRelative($fileName),
            null,
            0,
        );
    }
}

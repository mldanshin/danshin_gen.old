<?php

namespace App\Repositories\Person\Readable;

use App\Models\Eloquent\Photo as PhotoEloquentModel;
use App\Models\Person\Readable\Photo as PhotoModel;
use App\Repositories\Person\PhotoFileSystem;
use Illuminate\Support\Collection;

final class Photo
{
    public function __construct(private PhotoFileSystem $fileSystem)
    {
    }

    /**
     * @return Collection|PhotoModel[]|null
     */
    public function getByPerson(int $personId): ?Collection
    {
        $collect = PhotoEloquentModel::where("person_id", $personId)->orderBy("_order")->get()
            ->map(
                function ($item) use ($personId) {
                    $path = $this->fileSystem->getPath($personId, $item->file);
                    $this->fileSystem->existsFile($path);
                    return new PhotoModel(
                        $this->fileSystem->getUrl($personId, $item->file),
                        $path,
                        $item->_date
                    );
                }
            );

        if ($collect->count() > 0) {
            return $collect;
        } else {
            return null;
        }
    }
}

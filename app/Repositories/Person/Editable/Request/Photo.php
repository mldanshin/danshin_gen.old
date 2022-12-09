<?php

namespace App\Repositories\Person\Editable\Request;

use App\Models\Eloquent\Photo as PhotoEloquentModel;
use App\Models\Person\Editable\Photo as PhotoModel;
use App\Repositories\Person\PhotoFileSystem;
use Illuminate\Support\Collection;

final class Photo
{
    public function __construct(private PhotoFileSystem $fileSystem)
    {
    }

    /**
     * @param Collection|PhotoModel[]|null $collection
     */
    public function save(int $personId, ?Collection $collection): void
    {
        $filesNameStore = PhotoEloquentModel::where("person_id", $personId)->pluck("file");

        if ($collection === null) {
            $this->fileSystem->deletePersonFiles($personId, $filesNameStore);
        } else {
            $filesNameActual = $this->fileSystem->getBaseNames(
                $collection->map(fn($item) => $item->getUrl())
            );
            $this->fileSystem->deletePersonFiles($personId, $filesNameStore->diff($filesNameActual));
            $this->fileSystem->moveTemp($personId, $filesNameActual->diff($filesNameStore));
        }

        $this->changeDB($personId, $collection);
    }

    public function delete(int $personId): void
    {
        $this->fileSystem->deletePerson($personId);
    }

    /**
     * @param Collection|PhotoModel[] $collection
     */
    private function changeDB(int $personId, ?Collection $collection): void
    {
        PhotoEloquentModel::where("person_id", $personId)->delete();
        if ($collection !== null) {
            foreach ($collection as $item) {
                PhotoEloquentModel::create(
                    [
                    "person_id" => $personId,
                    "file" => $this->fileSystem->getBaseName($item->getPathRelative()),
                    "_date" => $item->getDate(),
                    "_order" => $item->getOrder()
                    ]
                );
            }
        }
    }
}

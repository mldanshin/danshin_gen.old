<?php

namespace App\Repositories\Person;

use App\Models\Download\Photo\FileArchive;
use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

final class PhotoFileSystem
{
    private const PATH_RELATIVE = "photo/";
    private const PATH_TEMP_RELATIVE = "photo_temp/";
    private string $path;
    private string $pathTemp;

    public function __construct(private Filesystem $disk)
    {
        $this->path = $disk->path(self::PATH_RELATIVE);
        $this->pathTemp = $disk->path(self::PATH_TEMP_RELATIVE);
        $this->createPath($this->path);
        $this->createPath($this->pathTemp);
    }

    public static function instance(): self
    {
        return new self(Storage::disk("public"));
    }

    public function getDisk(): Filesystem
    {
        return $this->disk;
    }

    public function getPathRelative(int $personId, string $fileName): string
    {
        return self::PATH_RELATIVE . "$personId/$fileName";
    }

    public function getPath(int $personId, string $fileName): string
    {
        $pathRelative = self::PATH_RELATIVE . "$personId/$fileName";
        return $this->disk->path($pathRelative);
    }

    public function getPathTempRelative(string $fileName): string
    {
        return self::PATH_TEMP_RELATIVE . $fileName;
    }

    public function getPathTemp(string $fileName): string
    {
        return $this->disk->path(self::PATH_TEMP_RELATIVE . $fileName);
    }

    public function getUrlTemp(string $fileName): string
    {
        return $this->disk->url(self::PATH_TEMP_RELATIVE . $fileName);
    }

    public function getUrl(int $personId, string $fileName): string
    {
        $pathRelative = self::PATH_RELATIVE . "$personId/$fileName";
        return $this->disk->url($pathRelative);
    }

    /**
     * @param  array|string[] $filesName
     * @return array|string[]
     */
    public function getUrls(int $personId, array $filesName): array
    {
        return array_map(
            function ($item) use ($personId) {
                return $this->getUrl($personId, $item);
            },
            $filesName
        );
    }

    public function putTemp(UploadedFile $file): string
    {
        $path = $this->disk->putFile(self::PATH_TEMP_RELATIVE, $file);
        $fileName = File::basename($path);
        return $fileName;
    }

    /**
     * @param Collection|string[] $filesName
     * @throws \Exception
     */
    public function moveTemp(int $personId, Collection $filesName): void
    {
        foreach ($filesName as $file) {
            $pathFileTemp = $this->getPathTemp($file);
            if (File::exists($pathFileTemp)) {
                $this->createPerson($personId);
                try {
                    $res = File::move($pathFileTemp, $this->getPath($personId, $file));
                    if ($res === false) {
                        throw new \Exception(self::class . ", error move temp file " . $file);
                    }
                } catch (\Exception) {
                    throw new \Exception(self::class . ", error move temp file " . $file);
                }
            } else {
                throw new \Exception(self::class . ", not found temp file " . $file);
            }
        }
    }

    public function getPathDirectoryTemp(): string
    {
        return $this->disk->path(self::PATH_TEMP_RELATIVE);
    }

    /**
     * @throws \Exception
     */
    public function existsFile(string $pathFile): bool
    {
        if (!File::exists($pathFile)) {
            throw new \Exception(self::class . ", not found file " . $pathFile);
        }

        return true;
    }

    /**
     * @throws \Exception
     */
    public function deletePerson(int $personId): bool
    {
        $pathRelative = self::PATH_RELATIVE . "$personId";
        if ($this->disk->exists($pathRelative)) {
            if ($this->disk->deleteDirectory($pathRelative) === false) {
                throw new \Exception(self::class . ", error deleting a folder person " . $personId);
            }
        }
        return true;
    }

    /**
     * @param Collection|string[] $filesName
     * @throws \Exception
     */
    public function deletePersonFiles(int $personId, Collection $filesName): bool
    {
        $filesPath = $filesName->map(fn($item) => self::PATH_RELATIVE . "$personId/$item");

        if ($this->disk->delete($filesPath->all()) === false) {
            throw new \Exception(self::class . ", error deleting files a folder person $personId");
        }
        return true;
    }

    /**
     * @param Collection|string[] $path
     * @return Collection|string[]
     */
    public function getBaseNames(Collection $path): Collection
    {
        return $path->map(fn($item) => File::basename($item));
    }

    public function getBaseName(string $path): string
    {
        return File::basename($path);
    }

    /**
     * @return FileArchive[]
     */
    public function getFilesArchive(): array
    {
        $directoriesSplFileInfo = File::allFiles($this->path);
        $func = function ($item) {
            $path = $item->getPathName();
            return new FileArchive($path, $this->createEntryNameForArchive($path));
        };
        return array_map($func, $directoriesSplFileInfo);
    }

    private function createPerson(int $personId): void
    {
        $path = self::PATH_RELATIVE . "$personId";
        if (!$this->disk->exists($path)) {
            $this->disk->makeDirectory($path);
        }
    }

    private function createPath(string $path): void
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0777);
        }
    }

    private function createEntryNameForArchive(string $path): string
    {
        $array = explode("/", $path);
        $countArray = count($array);
        return $array[$countArray - 2] . "/" . $array[$countArray - 1];
    }
}

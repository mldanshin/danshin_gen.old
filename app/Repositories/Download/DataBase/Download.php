<?php

namespace App\Repositories\Download\DataBase;

use App\Repositories\Download\FileSystem;

final class Download
{
    private FileSystem $fileSystem;
    private string $path;

    public function __construct(?FileSystem $fileSystem = null)
    {
        $this->initializeFileSystem($fileSystem);
        $this->initializePath();
        $this->createFile();
    }

    public function getPath(): string
    {
        return $this->path;
    }

    private function initializeFileSystem(?FileSystem $fileSystem): void
    {
        if ($fileSystem === null) {
            $this->fileSystem = FileSystem::instance();
        } else {
            $this->fileSystem = $fileSystem;
        }
    }

    private function initializePath(): void
    {
        $this->path = $this->fileSystem->getPath() . "db-genealogy.sql";
    }

    /**
     * @throws \Exception
     */
    private function createFile(): void
    {
        $res = exec($this->buildQuery());

        if ($res === false) {
            throw new \Exception("An error occurred when creating a dump of the database file");
        }
    }

    /**
     * @throws \Exception
     */
    private function buildQuery(): string
    {
        $dbConnection = config("database.connection_current");

        switch ($dbConnection) {
            case "mysql":
                $dbUserName = config("database.connections.mysql.username");
                $dbPassword = config("database.connections.mysql.password");
                $dbName = config("database.connections.mysql.database");
                return "mysqldump -u$dbUserName -p$dbPassword --skip-compact $dbName > {$this->path}";
            default:
                throw new \Exception("Unknown type of database connection: '$dbConnection'");
        }
    }
}

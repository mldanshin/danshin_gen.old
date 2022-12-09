<?php

namespace App\Backup\Database;

use Spatie\DbDumper\Databases\MySql;

final class Storage
{
    public readonly string $path;

    public function __construct()
    {
        $fileDumpName = config("database.connections.mysql.database");
        $this->path = storage_path("app") . "/backup/" . $fileDumpName . ".sql";
    }

    public function createDupm(): void
    {
        $databaseName = config("database.connections.mysql.database");
        $userName = config("database.connections.mysql.username");
        $password = config("database.connections.mysql.password");

        MySql::create()
            ->setDbName($databaseName)
            ->setUserName($userName)
            ->setPassword($password)
            ->dumpToFile($this->path);
    }
}

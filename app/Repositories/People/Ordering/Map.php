<?php

namespace App\Repositories\People\Ordering;

use App\Models\Pair;
use Illuminate\Support\Collection;

/**
 * creates an object by key that implements the OrderingContract interface
 * from the ordering_map
 */
final class Map
{
    private const FILE_NAME = "ordering_map.php";
    /**
     * @var mixed[] $map
     */
    private array $map;
    /**
     * @var Collection|Pair[] $items
     */
    private Collection $items;

    public function __construct()
    {
        $this->initializeMap();
        $this->initializeItems();
    }

    public function getSorter(?int $id): OrderingContract
    {
        if ($id === null) {
            $id = $this->getKeyDefault();
        }

        $className = $this->map[$id]["class"];
        $classFullName = "App\\Repositories\\People\\Ordering\\" . $className;
        return new $classFullName();
    }

    /**
     * @return Collection|Pair[]
     */
    public function get(): Collection
    {
        return $this->items;
    }

    /**
     * @return int[]
     */
    public function getKeys(): array
    {
        return array_keys($this->map);
    }

    public function getKeyDefault(): int
    {
        return config("app.people_order");
    }

    private function initializeMap(): void
    {
        $this->map = include self::FILE_NAME;
    }

    private function initializeItems(): void
    {
        $array = [];
        foreach ($this->map as $key => $value) {
            $array[$key] = new Pair($key, $value["label"]);
        }
        $this->items = collect($array);
    }
}

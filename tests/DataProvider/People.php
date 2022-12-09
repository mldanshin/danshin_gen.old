<?php

namespace Tests\DataProvider;

use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Repositories\People\Ordering\Map as OrderingMap;

trait People
{
    /**
     * @return array|int[]
     */
    private function peopleId(): array
    {
        return PeopleEloquentModel::pluck("id")->all();
    }

    /**
     * @return array|int[]
     */
    private function peopleIdWrong(): array
    {
        $idSuccess = PeopleEloquentModel::pluck("id")->all();
        $idRand = [];
        for ($i = 0; $i < 6; $i++) {
            $idRand[] = rand(-3000, -1000);
        }
        return array_diff($idRand, $idSuccess);
    }

    private function randomParent(PeopleEloquentModel $person): ?int
    {
        $parents = $person->parents()->get();
        if ($parents->isEmpty()) {
            return null;
        } else {
            return $parents->random()->parent_id;
        }
    }

    private function randomExceptParent(int|string $id): int
    {
        $parents = PeopleEloquentModel::find($id)->parents()->get()->pluck("parent_id")->all();

        $query = PeopleEloquentModel::where("id", "<>", $id);
        if (!empty($parents)) {
            $query = $query->whereNotIn("id", $parents);
        }

        return $query->pluck("id")->random();
    }

    private function filterOrderingDataProvider(): array
    {
        $orderingMap = new OrderingMap();

        $peopleOrder = array_merge($orderingMap->getKeys(), [null]);

        $array = [
            "people_order" => $this->faker->randomElement($peopleOrder),
            "people_search" => $this->faker->randomElement([$this->faker->word(), null])
        ];

        return $array;
    }
}

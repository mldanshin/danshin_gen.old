<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $person_id
 * @property int $role_id
 */
final class PersonRole extends Model
{
    use HasFactory;

    protected $table = "people_role";
    public $timestamps = false;
    protected $fillable = [
        "person_id",
        "role_id"
    ];

    /**
     * @throws \Exception
     */
    public static function getInstanceOrDefault(int $personId): self
    {
        $model = self::where("person_id", $personId)->first();
        if ($model === null) {
            $defaultRoleId = config("auth.person_role_default");
            if (UserRole::find($defaultRoleId) === null) {
                throw new \Exception("The role_id=$defaultRoleId is missing from the database table people_role");
            }

            $model = new PersonRole([
                "person_id" => $personId,
                "role_id" => $defaultRoleId
            ]);
        }
        return $model;
    }
}

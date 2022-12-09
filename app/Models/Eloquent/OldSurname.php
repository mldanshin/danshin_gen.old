<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $person_id
 * @property string $surname
 * @property int $_order
 * @property string|null $created_at
 * @property string|null $updated_at
 */
final class OldSurname extends Model
{
    use HasFactory;

    protected $fillable = [
        "person_id",
        "surname",
        "_order",
    ];
}

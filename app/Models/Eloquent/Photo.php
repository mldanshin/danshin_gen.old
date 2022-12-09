<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $person_id
 * @property string $file
 * @property string|null $_date
 * @property int $_order
 * @property string $created_at
 * @property string $updated_at
 */
final class Photo extends Model
{
    use HasFactory;

    protected $table = "photo";
    protected $fillable = [
        "person_id",
        "file",
        "_date",
        "_order"
    ];
}

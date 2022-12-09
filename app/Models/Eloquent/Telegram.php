<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $person_id
 * @property string $telegram_id
 * @property string|null $telegram_username
 * @property string|null $created_at
 * @property string|null $updated_at
 */
final class Telegram extends Model
{
    use HasFactory;

    protected $table = "telegram";
    protected $fillable = [
        "person_id",
        "telegram_id",
        "telegram_username",
    ];
}

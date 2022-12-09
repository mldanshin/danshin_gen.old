<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $slug
 */
final class UserRole extends Model
{
    use HasFactory;

    protected $table = "users_role";
    public $timestamps = false;
    protected $fillable = [
        "slug"
    ];
}

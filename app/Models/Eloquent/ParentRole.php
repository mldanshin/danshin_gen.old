<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $slug
 */
final class ParentRole extends Model
{
    use HasFactory;

    protected $table = "parent_roles";
    public $timestamps = false;
}

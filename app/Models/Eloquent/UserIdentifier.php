<?php

namespace App\Models\Eloquent;

use App\Models\Auth\UserIdentifierType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $slug
 */
final class UserIdentifier extends Model
{
    use HasFactory;

    protected $table = "users_identifiers";
    public $timestamps = false;

    public static function getIdByContent(string $string): ?UserIdentifierType
    {
        if (empty($string)) {
            return null;
        } elseif (self::verifyTowardPhone($string)) {
            return UserIdentifierType::PHONE;
        } elseif (self::verifyTowardEmail($string)) {
            return UserIdentifierType::EMAIL;
        } else {
            return null;
        }
    }

    private static function verifyTowardPhone(string $string): bool
    {
        $pattern = "#^[0-9]+$#i";
        if (preg_match($pattern, $string)) {
            return true;
        } else {
            return false;
        }
    }

    private static function verifyTowardEmail(string $string): bool
    {
        $pattern = "#.+@.+\..+#i";
        if (preg_match($pattern, $string)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @throws \Exception
     */
    public function getType(): UserIdentifierType
    {
        return match ($this->id) {
            1 => UserIdentifierType::EMAIL,
            2 => UserIdentifierType::PHONE,
            default => throw new \Exception("Invalid value userIdentifierType={$this->id}")
        };
    }
}

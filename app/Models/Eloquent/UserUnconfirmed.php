<?php

namespace App\Models\Eloquent;

use App\Models\Auth\UserIdentifierType;
use App\Models\Eloquent\UserIdentifier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

/**
 * @property int $id
 * @property int $identifier_id
 * @property string $identifier
 * @property string $password
 * @property string $timestamp
 * @property int $attempts
 * @property string $code
 * @property string $repeat_timestamp
 * @property int $repeat_attempts
 */
final class UserUnconfirmed extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = "users_unconfirmed";
    public $timestamps = false;
    protected $fillable = [
        "identifier_id",
        "identifier",
        "password",
        "timestamp",
        "attempts",
        "code",
        "repeat_timestamp",
        "repeat_attempts"
    ];

    public function routeNotificationForMail(Notification $notification): ?string
    {
        if ($this->getIdentifierType() === UserIdentifierType::EMAIL) {
            return $this->identifier;
        } else {
            return null;
        }
    }

    public function routeNotificationForSms(Notification $notification): ?string
    {
        if ($this->getIdentifierType() === UserIdentifierType::PHONE) {
            return $this->identifier;
        } else {
            return null;
        }
    }

    /**
     * @throws \Exception
     */
    public function getIdentifierType(): UserIdentifierType
    {
        return match ($this->identifier_id) {
            1 => UserIdentifierType::EMAIL,
            2 => UserIdentifierType::PHONE,
            default => throw new \Exception("Invalid value userIdentifierType={$this->identifier_id}")
        };
    }
}

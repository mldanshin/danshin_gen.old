<?php

namespace App\Models\Eloquent;

use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

/**
 * @property int $id
 * @property int $person_id
 * @property string $password
 * @property string|null $remember_token
 * @property string|null $created_at
 * @property string|null $updated_at
 */
final class User extends AuthUser
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        "person_id",
        "password",
        "remember_token"
    ];

    public function person(): hasOne
    {
        return $this->hasOne(People::class, "id", "person_id");
    }

    public function phone(): HasMany
    {
        return $this->hasMany(Phone::class, "person_id", "person_id");
    }

    public function telegram(): HasMany
    {
        return $this->hasMany(Telegram::class, "person_id", "person_id");
    }

    public function subscriptionEvent(): hasOne
    {
        return $this->hasOne(SubscriberEvent::class, "user_id", "id");
    }

    public function getRole(): PersonRole
    {
        return PersonRole::getInstanceOrDefault($this->person_id);
    }

    public function isSubscription(): bool
    {
        return $this->subscriptionEvent()->exists();
    }

    public function routeNotificationForTelegram(Notification $notification): ?string
    {
        return $this->telegram()->first()?->telegram_id;
    }
}

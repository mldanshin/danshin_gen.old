<?php

namespace App\Repositories\Auth\Registration;

use App\Models\Auth\Registration\ConfirmationCodeForm;
use App\Models\Auth\Registration\FormRequest;
use App\Models\Eloquent\Email;
use App\Models\Eloquent\Phone;
use App\Models\Eloquent\User;
use App\Models\Eloquent\UserUnconfirmed;
use App\Repositories\Auth\Identifier;
use Illuminate\Support\Facades\Hash;

final class Registration
{
    public function __construct(private Identifier $identifierRepository)
    {
    }

    /**
     * @throws \Exception
     */
    public function confirmUser(string $id): User
    {
        $userConfirmed = UserUnconfirmed::find($id);
        $personId = $this->identifierRepository->getPersonId(
            $userConfirmed->getIdentifierType(),
            $userConfirmed->identifier
        );

        if ($personId === null) {
            throw new \Exception("Invalid value idUserUnconfirmed = $id");
        }

        $this->deleteUsersUnconfirmed($userConfirmed, $personId);
        return $this->register($personId, $userConfirmed->password);
    }

    public function getUserUnconfirmedOrFail(string $idUser): UserUnconfirmed
    {
        return UserUnconfirmed::findOrFail($idUser);
    }

    public function createUserUnconfirmed(FormRequest $request): UserUnconfirmed
    {
        return new UserUnconfirmed([
            "identifier_id" => $request->getIdentifierType()->value,
            "identifier" => $request->getIdentifier(),
            "password" => Hash::make($request->getPassword()),
            "timestamp" => $this->getTimestamp(),
            "attempts" => $this->getAttempts(),
            "code" => $this->getCode(),
            "repeat_timestamp" => $this->getRepeatTimestampStart(),
            "repeat_attempts" => 0
        ]);
    }

    public function repeatUserUnconfirmed(string $idUser): UserUnconfirmed
    {
        $user = $this->getUserUnconfirmedOrFail($idUser);

        $user->timestamp = $this->getTimestamp();
        $user->attempts = $this->getAttempts();
        $user->code = $this->getCode();
        $user->repeat_attempts++;
        $user->repeat_timestamp = $this->getRepeatTimestampEnlarged($user->repeat_attempts);

        return $user;
    }

    public function getConfirmationCodeForm(string $idUser): ?ConfirmationCodeForm
    {
        $user = $this->getUserUnconfirmedOrFail($idUser);

        if (!$this->verifyTime((int)$user->timestamp)) {
            return null;
        }

        if (!$this->verifyAttempts($user->attempts)) {
            return null;
        }

        return new ConfirmationCodeForm(
            $user->id,
            $user->attempts,
            (int)$user->timestamp - time(),
            $this->getRepeatTimestampInterval($user->repeat_timestamp),
        );
    }

    public function verifyRepeatTimestamp(string $idUser): ?bool
    {
        $user = UserUnconfirmed::find($idUser);
        if (empty($user)) {
            return null;
        }

        if (time() < $user->repeat_timestamp) {
            return false;
        } else {
            return true;
        }
    }

    public function reduceAttempts(UserUnconfirmed $user): void
    {
        $user->attempts--;
        $user->save();
    }

    public function getRepeatTimestampInterval(string $repeatTimestamp): int
    {
        return ((int)$repeatTimestamp - time());
    }

    public function verifyCode(string $expected, string $actual): bool
    {
        if ($expected === $actual) {
            return true;
        } else {
            return false;
        }
    }

    public function verifyTime(int $timestamp): bool
    {
        if ($timestamp <= time()) {
            return false;
        } else {
            return true;
        }
    }

    public function verifyAttempts(int $attempts): bool
    {
        if ($attempts <= 0) {
            return false;
        } else {
            return true;
        }
    }

    private function register(int $person_id, string $password): User
    {
        return User::create([
            'person_id' => $person_id,
            'password' => $password
        ]);
    }

    private function deleteUsersUnconfirmed(UserUnconfirmed $userConfirmed, int $personId): void
    {
        $userConfirmed->delete();

        $phones = Phone::where("person_id", $personId)->pluck("name")->all();
        UserUnconfirmed::whereIn("identifier", $phones)->delete();

        $emails = Email::where("person_id", $personId)->pluck("name")->all();
        UserUnconfirmed::whereIn("identifier", $emails)->delete();
    }

    private function getCode(): string
    {
        return (string)random_int(10000, 99999);
    }

    /**
     * @throws \Exception
     */
    private function getAttempts(): int
    {
        $attempts = config("auth.confirmation_user.attempts");
        if (!($attempts >= 1 && $attempts <= 3)) {
            throw new \Exception("invalid value attempts");
        }

        return $attempts;
    }

    /**
     * @throws \Exception
     */
    private function getTimestamp(): string
    {
        $seconds = config("auth.confirmation_user.time");
        if (!($seconds >= 30 && $seconds <= 300)) {
            throw new \Exception("invalid time interval");
        }

        return (string)(time() + $seconds);
    }

    /**
     * @throws \Exception
     */
    private function getRepeatTimestampStart(): string
    {
        $seconds = config("auth.confirmation_user.repeat_interval_start");
        if ($seconds < 30) {
            throw new \Exception("invalid time repeat start");
        }

        return (string)(time() + $seconds);
    }

    /**
     * @throws \Exception
     */
    private function getRepeatTimestampEnlarged(int $repeatAttempts): string
    {
        $ratio = config("auth.confirmation_user.repeat_interval_ratio");
        if ($ratio < 2) {
            throw new \Exception("invalid time repeat ratio");
        }

        $interval = config("auth.confirmation_user.repeat_interval_start");
        if ($interval < 30) {
            throw new \Exception("invalid time repeat start");
        }

        for ($i = 0; $i < $repeatAttempts; $i++) {
            $interval = $interval * $ratio;
        }

        return (string)(time() + $interval);
    }
}

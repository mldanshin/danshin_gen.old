<?php

namespace Database\Seeders\Testing;

use App\Models\Eloquent\UserUnconfirmed;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserUnconfirmedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create(1, 2, '8881112222', "password1", time() + 3600, 0, time() + 12000);
        $this->create(2, 2, '8883332222', "password2", time() - 2000, 1, time() - 4500);
        $this->create(3, 2, '8884442222', "password3", time() - 1000, 1, time() - 5000);
        $this->create(4, 2, '8885552222', "password3", time() + 1000, 1, time() + 6500);
        $this->create(5, 1, 'igor@fakemail.ru', "password3", time() + 2500, 2, time() + 10000);
    }

    private function create(
        int $id,
        int $identifierType,
        string $identifier,
        string $password,
        string $timestamp,
        int $attempts,
        string $repeatTimestamp
    ): void {
        $user = new UserUnconfirmed();
        $user->id = $id;
        $user->identifier_id = $identifierType;
        $user->identifier = $identifier;
        $user->password = Hash::make($password);
        $user->timestamp = $timestamp;
        $user->attempts = $attempts;
        $user->code = random_int(10000, 99999);
        $user->repeat_timestamp = $repeatTimestamp;
        $user->repeat_attempts = random_int(0, 2);
        $user->save();
    }
}

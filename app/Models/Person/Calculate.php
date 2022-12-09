<?php

namespace App\Models\Person;

final class Calculate
{
    private ?\DateInterval $age;
    private ?\DateInterval $intervalBirth;
    private ?\DateInterval $intervalDeath;

    public function __construct(\DateTime $today, string $birthDate, ?string $deathDate)
    {
        $this->initialize($today, $birthDate, $deathDate);
    }

    /**
     * the flow of age after death stops
     */
    public function getAge(): ?\DateInterval
    {
        return $this->age;
    }

    /**
     * continues to increase after death
     */
    public function getIntervalBirth(): ?\DateInterval
    {
        return $this->intervalBirth;
    }

    public function getIntervalDeath(): ?\DateInterval
    {
        return $this->intervalDeath;
    }

    /**
     * @throws \Exception
     */
    private function initialize(\DateTime $today, string $birthDate, ?string $deathDate): void
    {
        $todayFormat = $today->format("Y-m-d");

        if (empty($birthDate) || str_contains($birthDate, "?")) {
            $this->age = null;
            $this->intervalBirth = null;
        } else {
            if ($birthDate > $todayFormat) {
                throw new \Exception("the date of birth cannot be earlier than the current date");
            }

            if ($deathDate === null) {
                $this->age = $this->diffDate(new \DateTime($birthDate), $today);
            } elseif ($deathDate === "" || str_contains($deathDate, "?")) {
                $this->age = null;
            } else {
                if ($deathDate > $todayFormat) {
                    throw new \Exception("the date of death cannot be earlier than the current date");
                }
                if ($birthDate > $deathDate) {
                    throw new \Exception("the date of birth cannot be earlier than the date of death");
                }
                $this->age = $this->diffDate(
                    new \DateTime($birthDate),
                    new \DateTime($deathDate)
                );
            }

            $this->intervalBirth = $this->diffDate(new \DateTime($birthDate), $today);
        }

        if (empty($deathDate) || str_contains($deathDate, "?")) {
            $this->intervalDeath = null;
        } else {
            if ($deathDate > $todayFormat) {
                throw new \Exception("the date of death cannot be earlier than the current date");
            }
            $this->intervalDeath = $this->diffDate(new \DateTime($deathDate), $today);
        }
    }

    private function diffDate(\DateTime $dateStart, \DateTime $dateEnd): \DateInterval
    {
        return $dateEnd->diff($dateStart);
    }
}

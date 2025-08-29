<?php


namespace App\Service;

use App\Entity\User;


class DriverRatingService
{

    public function updateRating(User $driver, float $newNote)
    {


        $currentAverage = $driver->getAverageRating();
        $currentCount = $driver->getRatingCount();

        $newAverage = (($currentAverage * $currentCount) + $newNote) / ($currentCount + 1);
        $driver->setAverageRating($newAverage);
        $driver->setRatingCount($currentCount + 1);
    }
}

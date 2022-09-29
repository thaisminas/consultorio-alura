<?php

namespace App\Helper;

use App\Entity\Speciality;

class SpecialityFactory implements EntityFactoryInterface
{
    public function createEntity(string $json): Speciality
    {
        $dataJson = json_decode($json);
        $speciality = new Speciality();
        $speciality->setDescription($dataJson->description);

        return $speciality;
    }
}
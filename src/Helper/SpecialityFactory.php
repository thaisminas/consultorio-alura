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

    /**
     * @param $objetJson
     * @throws EntityFactoryException
     */
    private function checkIfDescriptionExists($objetJson): void
    {
        if (!property_exists($objetJson, 'descricao')) {
            throw new EntityFactoryException('A descrição de uma especialidade é obrigatória');
        }
    }
}
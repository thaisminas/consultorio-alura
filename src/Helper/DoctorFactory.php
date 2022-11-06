<?php

namespace App\Helper;

use App\Entity\Doctor;
use App\Repository\SpecialityRepository;

class DoctorFactory implements EntityFactoryInterface
{
    /**
     * @var SpecialityRepository
     */
    private $specialityRepository;

    public function __construct(SpecialityRepository $specialityRepository)
    {
        $this->specialityRepository = $specialityRepository;
    }

    public function createEntity(string $json): Doctor
    {
        $data = json_decode($json);

        $specialityId = $data->specialityId;

        $speciality = $this->specialityRepository->find($specialityId);

        if (is_null($speciality)) {
            throw new EntityFactoryException('Especialidade inexistente');
        }

        $doctor = new Doctor();

        $doctor->setCrm($data->crm);
        $doctor->setName($data->name);
        $doctor->setSpeciality($speciality);

        return $doctor;
    }


    private function checkIfAllPropertiesExist(object $objetoJson): void
    {
        if (!property_exists($objetoJson, 'nome')) {
            throw new EntityFactoryException('Médico precisa de nome');
        }

        if (!property_exists($objetoJson, 'crm')) {
            throw new EntityFactoryException('Médico precisa de CRM');
        }

        if (!property_exists($objetoJson, 'especialidadeId')) {
            throw new EntityFactoryException('Médico precisa de especialidade');
        }
    }
}
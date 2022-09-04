<?php

namespace App\Helper;

use App\Entity\Doctor;
use App\Repository\SpecialityRepository;

class DoctorFactory implements \App\Helper\EntityFactoryInterface
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

        $doctor = new Doctor();

        $doctor->setCrm($data->crm);
        $doctor->setName($data->name);
        $doctor->setSpeciality($speciality);

        return $doctor;
    }
}
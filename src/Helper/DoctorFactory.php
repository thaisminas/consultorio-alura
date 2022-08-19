<?php

namespace App\Helper;

use App\Entity\Doctor;
use App\Repository\SpecialityRepository;

class DoctorFactory
{
    public function __construct(SpecialityRepository $specialityRepository)
    {
        $this->specialityRepository = $specialityRepository;
    }

    public function createDoctor(string $json): Doctor
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
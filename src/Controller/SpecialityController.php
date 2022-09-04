<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\Speciality;
use App\Helper\ExtractDataRequest;
use App\Helper\SpecialityFactory;
use App\Repository\SpecialityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class SpecialityController extends BaseController
{

    public function __construct(EntityManagerInterface $entityManager,
                                SpecialityRepository $specialityRepository,
                                SpecialityFactory $factory,
                                ExtractDataRequest $extractDataRequest)
    {
        parent::__construct($entityManager, $factory ,$specialityRepository, $extractDataRequest );
    }

//    /**
//     * @Route("/especialidades/{id}", methods={"PUT"})
//     */
    public function changeEntityAlreadyExist(int $id, $entity)
    {
        /** @var Speciality $entityAlreadyExist */
        $entityAlreadyExist = $this->repository->find($id);

        if (is_null($entityAlreadyExist)) {
            throw new \InvalidArgumentException();
        }
        $entityAlreadyExist->setDescription($entity->getDescription());

        return $entityAlreadyExist;
    }

}
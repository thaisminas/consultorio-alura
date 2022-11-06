<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\Speciality;
use App\Helper\ExtractDataRequest;
use App\Helper\SpecialityFactory;
use App\Repository\SpecialityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;
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
                                ExtractDataRequest $extractDataRequest,
                                CacheItemPoolInterface $cache)
    {
        parent::__construct($entityManager, $factory ,$specialityRepository, $extractDataRequest, $cache );
    }

    public function changeEntityAlreadyExist(int $id, $entity): Speciality
    {
        /** @var Speciality $entityAlreadyExist */
        $entityAlreadyExist = $this->repository->find($id);

        if (is_null($entityAlreadyExist)) {
            throw new \InvalidArgumentException();
        }
        $entityAlreadyExist->setDescription($entity->getDescription());

        return $entityAlreadyExist;
    }

    public function updateExistingEntity(int $id, $entity)
    {
        /** @var Speciality $specialityExist */
        $specialityExist = $this->getDoctrine()->getRepository(Speciality::class)->find($id);
        $specialityExist->setDescription($entity->getDescription());

        return $specialityExist;
    }

    public function cachePrefix(): string
    {
        return '$speciality_';
    }

}
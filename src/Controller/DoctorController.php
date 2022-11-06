<?php


namespace App\Controller;

use App\Entity\Doctor;
use App\Helper\DoctorFactory;
use App\Helper\ExtractDataRequest;
use App\Repository\DoctorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DoctorController extends BaseController
{
    /**
     * @var DoctorFactory
     */
    private $doctorFactory;
    /**
     * @var DoctorRepository
     */
    private $doctorRepository;


    public function __construct(EntityManagerInterface $entityManager,
        DoctorFactory $doctorFactory,
        DoctorRepository $doctorRepository,
        ExtractDataRequest $extractDataRequest,
        CacheItemPoolInterface $cache
    ){
        parent::__construct($entityManager, $doctorFactory, $doctorRepository, $extractDataRequest, $cache) ;
        $this->doctorFactory = $doctorFactory;
        $this->doctorRepository = $doctorRepository;
    }

    public function changeEntityAlreadyExist(int $id, $entity): Doctor
    {
        /** @var Doctor $changeEntityAlreadyExist */
        $changeEntityAlreadyExist = $this->repository->find($id);
        if (is_null($changeEntityAlreadyExist)) {
            throw new \InvalidArgumentException();
        }

        $changeEntityAlreadyExist->setCrm($entity->getCrm());
        $changeEntityAlreadyExist->setName($entity->getName());

        return $changeEntityAlreadyExist;
    }

    /**
     * @Route ("/especialidades/{specialityId}/medicos", methods={"GET"})
     */
    public function queryForSpeciality(int $specialityId): Response
    {
        $doctor = $this->doctorRepository->findBy([
            'speciality' => $specialityId
        ]);

        return new JsonResponse($doctor);

    }

    public function updateExistingEntity(int $id, $entity)
    {
        /** @var Doctor $doctorExist */
        $doctorExist = $this->getDoctrine()->getRepository(Doctor::class)->find($id);
        $doctorExist->setName($entity->getName());
        $doctorExist->setCrm($entity->getCrm());
        $doctorExist->setSpeciality($entity->getSpeciality());

        return $doctorExist;
    }

    public function cachePrefix(): string
    {
        return 'doctor_';
    }
}
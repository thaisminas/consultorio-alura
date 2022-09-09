<?php


namespace App\Controller;

use App\Entity\Doctor;
use App\Helper\DoctorFactory;
use App\Helper\EntityFactoryInterface;
use App\Helper\ExtractDataRequest;
use App\Helper\ResponseFactory;
use App\Repository\DoctorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


abstract class BaseController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var EntityFactoryInterface
     */
    protected $factory;
    /**
     * @var ObjectRepository
     */
    protected $repository;

    /**
     * @var ExtractDataRequest;
     */
    private $extractDataRequest;


    public function __construct(EntityManagerInterface $entityManager,
                                EntityFactoryInterface $factory,
                                ObjectRepository $repository,
                                ExtractDataRequest $extractDataRequest
    ){
        $this->entityManager = $entityManager;
        $this->factory = $factory;
        $this->repository = $repository;
        $this->extractDataRequest = $extractDataRequest;
    }


    public function getAll(Request $request): JsonResponse
    {
        $informationFilter = $this->extractDataRequest->getDataFilter($request);
        $informationOrder = $this->extractDataRequest->getDataOrdernation($request);
        [$currentPage, $itemPerPage] = $this->extractDataRequest->getDataPage($request);


        $list = $this->repository->findBy($informationFilter, $informationOrder, $itemPerPage, ($currentPage -1) * $itemPerPage);

        $responseFactory = new ResponseFactory(true, $currentPage, $itemPerPage, $list);

        return $responseFactory->getResponse();
    }

    public function create(Request $request): Response
    {
        $bodyRequest = $request->getContent();
        $entity = $this->factory->createEntity($bodyRequest);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return new JsonResponse($entity);
    }

    public function getOne(int $id): Response
    {
        $entity = $this->repository->find($id);
        return new JsonResponse($entity);
    }

    public function remove(int $id): Response
    {
        $entity = $this->repository->find($id);
        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    public function change(int $id, Request $request): Response
    {
        $bodyRequest = $request->getContent();
        $entity = $this->factory->createEntity($bodyRequest);

        try {
            $entityAlreadExist = $this->changeEntityAlreadyExist($id, $entity);
            $this->entityManager->flush();

            $factory = new ResponseFactory(true, $entityAlreadExist, Response::HTTP_OK);

            return $factory->getResponse();
        } catch (\InvalidArgumentException $ex) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
    }

    abstract function changeEntityAlreadyExist(int $id, $entity);
}
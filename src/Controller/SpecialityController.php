<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\Speciality;
use App\Repository\SpecialityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class SpecialityController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SpecialityRepository
     */
    private $specialityRepository;

    public function __construct(EntityManagerInterface $entityManager, SpecialityRepository $specialityRepository)
    {
        $this->entityManager = $entityManager;
        $this->specialityRepository = $specialityRepository;
    }

    /**
     * @Route("/especialidades", methods={"POST"})
     */
    public function createSpeciality(Request $request): Response
    {
        $bodyRequest = $request->getContent();
        $bodyJson = json_decode($bodyRequest);

        $speciality = new Speciality();
        $speciality->setDescription($bodyJson->description);

        $this->entityManager->persist($speciality);
        $this->entityManager->flush();

        return new JsonResponse($speciality);
    }

    /**
     * @Route ("/especialidades", methods={"GET"})
     */
    public function getAllSpeciality(Request $request): Response
    {
        $specialityRepository = $this->getDoctrine()->getRepository(Speciality::class);

        $specialityList = $specialityRepository->findAll();
        return new JsonResponse($specialityList);
    }

    /**
     * @Route("/especialidades/{id}", methods={"GET"})
     */

    public function getOneSpeciality(int $id): Response
    {
        return new JsonResponse($this->specialityRepository->find($id));
    }

    /**
     * @Route("/especialidades/{id}", methods={"PUT"})
     */
    public function changeSpeciality(int $id, Request $request): Response
    {
        $body = $request->getContent();
        $bodyJson = json_decode($body);

        $speciality = $this->specialityRepository->find($id);

        $speciality->setDescription($bodyJson->description);

        $this->entityManager->flush();

        return new JsonResponse($speciality);
    }


    /**
     * @Route("/especialidades/{id}", methods={"DELETE"})
     */
    public function removeSpeciality(int $id)
    {
        $speciality = $this->specialityRepository->find($id);;

        $this->entityManager->remove($speciality);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);

    }



}
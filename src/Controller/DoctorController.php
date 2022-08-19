<?php


namespace App\Controller;

use App\Entity\Doctor;
use App\Helper\DoctorFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DoctorController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var DoctorFactory
     */
    private $doctorFactory;


    public function __construct(EntityManagerInterface $entityManager, DoctorFactory $doctorFactory)
    {
        $this->entityManager = $entityManager;
        $this->doctorFactory = $doctorFactory;
    }

    /**
     * @Route ("/medicos", methods={"POST"})
     */
    public function createDoctor(Request $request): Response
    {
        $body = $request->getContent();
        $doctor = $this->doctorFactory->createDoctor($body);

        $this->entityManager->persist($doctor);
        $this->entityManager->flush();

        return new JsonResponse($doctor);
    }

    /**
     * @Route ("/medicos", methods={"GET"})
     */
    public function getAllDoctor(Request $request): Response
    {
        $repositoryDoctors = $this->getDoctrine()->getRepository(Doctor::class);

        $doctorList = $repositoryDoctors->findAll();
        return new JsonResponse($doctorList);
    }

    /**
     * @Route ("/medicos/{id}", methods={"GET"})
     */
    public function getOneDoctor(int $id): Response
    {
        $doctor = $this->findDoctor($id);

        $codeReturn = is_null($doctor) ? Response::HTTP_NO_CONTENT : 200;

        return new JsonResponse($doctor, $codeReturn);

    }

    /**
     * @Route("/medicos/{id}", methods={"PUT"})
     */
    public function changeDoctor(int $id, Request $request): Response
    {
        $body = $request->getContent();

        $doctorSend = $this->doctorFactory->createDoctor($body);

        $doctor = $this->findDoctor($id);

        if(is_null($doctor)){
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        //Está alterando o dados recebido na requisicao no banco
        $doctor->setCrm($doctorSend->getCrm());
        $doctor->setName($doctorSend->getName());

        //Persistindo as alterações. Nao é necessario o persist pois já está sendo observado pelo doctrine.
        $this->entityManager->flush();

        return new JsonResponse($doctor);

    }

    /**
     * @Route("/medicos/{id}", methods={"DELETE"})
     */
    public function removeDoctor(int $id)
    {
        $doctor = $this->findDoctor($id);

        $this->entityManager->remove($doctor);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);

    }


    public function findDoctor(int $id)
    {
        $doctorRepository = $this->getDoctrine()->getRepository(Doctor::class);
        $doctor = $doctorRepository->find($id);

        return $doctor;
    }
}
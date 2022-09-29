<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginController extends AbstractController
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;


    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $encoder)
    {
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function index(Request $request): Response
    {
        $dadosEmJson = json_decode($request->getContent());

        if(is_null($dadosEmJson->usuario) || is_null($dadosEmJson->senha)){
            return new JsonResponse([
                'erro' => 'Favor enviar usuario e senha'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $user = $this->userRepository->findOneBy([
            'username' => $dadosEmJson->usuario
        ]);

        if(!$this->encoder->isPasswordValid($user, $dadosEmJson->senha)){
            return new JsonResponse([
                'erro' => 'Usuario ou senha invalido',
                Response::HTTP_UNAUTHORIZED
            ]);
        }

        $token = JWT::encode(['username' => $user->getUsername()], 'chave', 'HS256');

        return new JsonResponse(['acess_token' => $token]);

    }
}

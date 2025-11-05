<?php

namespace App\Controller\API;

use App\Managers\SessionManager;
use App\Services\ErrorHandler;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class LoginController extends AbstractController
{
    private SessionManager $sessionManager;

    public function __construct(
        SessionManager           $sessionManager,
    )
    {
        $this->sessionManager = $sessionManager;
    }


    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        // Execute login method
        try {
            $response = $this->sessionManager->login(json_decode($request->getContent()));
        } catch (Exception $e) {
            return ErrorHandler::handleException($e);
        }

        // Return the right data if everything is alright, else return an error
        if ($response){

            return $this->json([
                "user" => $response['user'],
                "token" => $response['token']
            ], 200, [
                'Authorization' => $response['token']
            ],
                [
                    'groups' => ['users.read'],
                ]
            );
        } else {

            return throw new BadCredentialsException('User or password incorrect.');
        }
    }

    #[Route('/api/logout', name: 'app_logout', methods: ['GET', 'POST'])]
    public function logout(Request $request): JsonResponse
    {
        // fetch user token and unlog them
        $token = $request->headers->get('Authorization');

        return $this->sessionManager->logout($token, json_decode($request->getContent()));
    }

}


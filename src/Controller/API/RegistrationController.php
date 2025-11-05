<?php

namespace App\Controller\API;

use App\Managers\RegistrationManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegistrationController extends AbstractController

{
    private RegistrationManager $registrationManager;

    public function __construct(
        RegistrationManager             $registrationManager,
    )
    {
        $this->registrationManager = $registrationManager;
    }
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {

        if ($response = $this->registrationManager->register(json_decode($request->getContent(), true))) {

            return $response;
        } else {

            return $this->registrationManager->register(json_decode($request->getContent(), true));
        }
    }
}
<?php

namespace App\Controller\API;

use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/api/me', name: 'app_me')]
    public function me(): JsonResponse
    {

        return $this->json($this->getUser(), 200, [], [
            'groups' => ['users.read']
        ]);
    }

    #[Route('/api/users', name: 'app_users')]
    public function users(UserRepository $userRepository): JsonResponse
    {
        try {
            $users = $userRepository->findAll();
        } catch (Exception $e) {
            return new JsonResponse([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }

        return $this->json($users, 200, [], [
            'groups' => ['users.read']
        ]);
    }
}

<?php

namespace App\Managers;

use App\DBAL\UserType;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SessionManager extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(EntityManagerInterface $entityManager, JWTTokenManagerInterface $jwtManager)
    {
        $this->entityManager = $entityManager;
        $this->jwtManager = $jwtManager;
    }

    public function utf8ize( $mixed ) {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = $this->utf8ize($value);
            }
        } elseif (is_string($mixed)) {
            return mb_convert_encoding($mixed, "UTF-8", "UTF-8");
        }
        return $mixed;
    }

    public function login($request): bool|array {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        // Check for empty credentials
        if (empty($credentials[UserType::EMAIL]) || empty($credentials[UserType::PASSWORD])) {
            throw new BadRequestHttpException('Email and password must be provided.');
        }

        // Fetch user by email
        $user = $this->entityManager->getRepository(Users::class)->findOneBy([
            UserType::EMAIL => $credentials[UserType::EMAIL]
        ]);

        // If user not found
        if (!$user) {
            throw new NotFoundHttpException('User not found.');
        }

        // Verify password
        if (password_verify($credentials[UserType::PASSWORD], $user->getPassword())) {
            try {
                // Create JWT token
                $token = $this->jwtManager->create($user);
                // Check if the token was created successfully
                if (!$token) {
                    throw new \RuntimeException('Failed to create token.');
                }

                // Clear sensitive data and set remember token
                $user->eraseCredentials();
                $user->setRememberToken($token);

                // Persist changes
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                // Return token and username
                return [
                    'token' => $token,
                    'user' => $user->getUsername()
                ];
            } catch (\Exception $e) {
                throw new \RuntimeException('Error generating token: ' . $e->getMessage());
            }
        }

        // If password does not match
        throw new BadCredentialsException('User or password incorrect.');
    }


    public function logout($token, $request): JsonResponse {
        $apiToken = str_replace('Bearer ', '', $token);

        $email = $request->email ?? null;

        // Ensure the email is provided
        if (!$email) {
            throw new BadRequestHttpException('Bad credentials.');
        }

        $currentUser = $this->entityManager->getRepository(Users::class)->findOneBy([
            'remember_token' => $apiToken,
        ]);

        if (!$currentUser) {
            throw new NotFoundHttpException('User not found.');
        }

        try {
            $currentUser->eraseCredentials();
            $this->entityManager->persist($currentUser);
            $this->entityManager->flush();

            return new JsonResponse(['message' => 'User logged out successfully.'], Response::HTTP_OK);

        } catch (\Exception $e) {

            return new JsonResponse(['code' => $e->getMessage(), 'message' => 'Something\'s wrong.'], Response::HTTP_OK);
        }
    }

}
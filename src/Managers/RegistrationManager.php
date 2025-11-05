<?php

namespace App\Managers;

use App\DBAL\UserType;
use App\Entity\Users;
use App\Services\DataValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationManager extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    private DataValidator $dataValidator;

    public function __construct(EntityManagerInterface      $entityManager,
                                UserPasswordHasherInterface $passwordHasher,
                                DataValidator               $dataValidator)
    {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
        $this->dataValidator = $dataValidator;
    }

    public function register($userData): bool|JsonResponse {

        if (!$this->verifyRequiredData($userData)) {

            return new JsonResponse(['message' => 'Missing required fields.'], Response::HTTP_BAD_REQUEST);
        }

        if (!$userData[UserType::LEGAL_TERMS]) {

            return new JsonResponse(['message' => 'Missing required fields.'], Response::HTTP_BAD_REQUEST);
        }
        $user = new Users();

        if ($this->checkForDuplicates($userData)->getStatusCode() != 200) {
            $content = json_decode($this->checkForDuplicates($userData)->getContent(), true);

            return new JsonResponse([
                'Message' => $content['Message']], 409);
        }
        if ($this->dataValidator->registrationDataValidation($userData)) {
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $userData[UserType::PASSWORD]
            );

            $user->setPassword($hashedPassword)
                ->setEmail($userData[UserType::EMAIL])
                ->setLastname($userData[UserType::LASTNAME])
                ->setFirstname($userData[UserType::FIRSTNAME])
                ->setUsername($userData[UserType::USERNAME])
                ->setAddress($userData[UserType::ADDRESS])
                ->setCity($userData[UserType::CITY])
                ->setRole(["ROLE_USER"])
                ->setPostalCode(($userData[UserType::POSTAL_CODE]))
                ->setIpAddress($userData[UserType::IP_ADDRESS])
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable())
                ->setIpAddress('127.0.0.1')
                ->setPhoneNumber(($userData[UserType::PHONE_NUMBER]))
                ->setTermsAccepted(1)
                ->setNewsletterSubscribed($userData[UserType::NEWSLETTER_SUBSCRIBED]);
            // TODO : Replace "setIpAddress" by the actual User IP, hard written IP only used in dev for testing purposes.

            try {
                $this->entityManager->persist($user);
                $this->entityManager->flush();
            } catch (\Exception $e) {
                return new JsonResponse([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage()
                ]);
            }

            return new JsonResponse("Users created successfully.");
        } else {

            return $this->dataValidator->registrationDataValidation($userData);
        }
    }
    private function verifyRequiredData($userData): bool {
        $requiredFields = [UserType::EMAIL, UserType::PASSWORD, UserType::FIRSTNAME, UserType::USERNAME, UserType::LASTNAME, UserType::CITY, UserType::POSTAL_CODE, UserType::LEGAL_TERMS];

        if (array_diff_key(array_flip($requiredFields), $userData)) {

            return false;
        }

        return true;
    }

    private function checkForDuplicates($userData): JsonResponse {
        $repository = $this->entityManager->getRepository(Users::class);

        if ($repository->findOneBy(['email' => $userData[UserType::EMAIL]])) {

            return new JsonResponse([
                'Message' => 'L\'adresse Email est déjà enregistrée.'
            ], 409);
        };

        if ($repository->findOneBy(['username' => $userData[UserType::USERNAME]])) {

            return new JsonResponse([
                'Message' => 'Le nom d\'utilisateur n\'est pas disponible.'
            ], 409);
        }

        return new JsonResponse([
            'Message' => 'Ok'
        ], 200);
    }
}
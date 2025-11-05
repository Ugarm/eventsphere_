<?php

namespace App\Security;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class LoginAuthenticator extends AbstractAuthenticator
{
    private EntityManagerInterface $entityManager;
    public function __construct(
        EntityManagerInterface      $entityManager,
    ) {
        $this->entityManager = $entityManager;
    }
    // Methods to know if authentication is supported
    public function supports(Request $request): ?bool
    {
        if ($user = $this->entityManager->getRepository(Users::class)->findOneBy([
            'email' => json_decode($request->getContent())->email
        ])) {

            return json_decode($request->getContent())->email && password_verify(json_decode($request->getContent())->password, $user->getPassword());
        }

        throw new BadRequestHttpException("Something\'s wrong.");
    }

    public function authenticate(Request $request): Passport
    {
        if ($currentUser = $this->entityManager->getRepository(Users::class)->findOneBy([
            'email' => json_decode($request->getContent())->email
        ])) {
            $identifier = $currentUser->getUserIdentifier();

            // TODO : Ajouter l'enum "UserType::IS_CONNECTED"
            return new SelfValidatingPassport(
                new UserBadge($identifier)
            );
        }

        throw new BadRequestHttpException("Something\'s wrong.");
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {

        return new JsonResponse([
            'message' => $exception->getMessage(),
            'location' => 'Login authentication'
        ], Response::HTTP_UNAUTHORIZED);
    }
}

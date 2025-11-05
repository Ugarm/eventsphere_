<?php

namespace App\Security;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class APIAuthenticator extends AbstractAuthenticator
{
    private $entityManager;
    public function __construct(
        EntityManagerInterface      $entityManager,
    ) {
        $this->entityManager = $entityManager;
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'api_register' ||
            ($request->headers->has('Authorization') && $request->headers->get('Authorization') !== 'null');

    }

    public function authenticate(Request $request): Passport
    {
        $apiToken = str_replace('Bearer ', '', $request->headers->get('Authorization'));
        dd($request->getContent());
        if (!$apiToken) {

            throw new CustomUserMessageAuthenticationException('No API token provided');
        }

        $userIdentifier = $this->entityManager->getRepository(Users::class)->findOneBy(['remember_token' => $apiToken]);

        if (!$userIdentifier) {
            throw new CustomUserMessageAuthenticationException('User associated with the token could not be found');
        }

        if (!$userIdentifier->getRememberToken()) {
            throw new CustomUserMessageAuthenticationException('API token could not be found');
        }

        $identifier = $userIdentifier->getUserIdentifier();

        return new Passport(
            new UserBadge($userIdentifier->getUserIdentifier()),
            new PasswordCredentials($credentials['password'])
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {

        return new JsonResponse([
            'message' => $exception->getMessage(),
            'location' => 'API authentication'
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function removeAllWhitespace(string $input): string {
        return preg_replace('/\s+/', '', $input);
    }

}
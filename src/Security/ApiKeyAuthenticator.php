<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use App\Repository\UserRepository;

class ApiKeyAuthenticator extends AbstractAuthenticator
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
        return true;
    }

    public function authenticate(Request $request): Passport
    {
        $apiToken = $request->headers->get('Authorization');
        if (null === $apiToken) {
            throw new CustomUserMessageAuthenticationException('token not found');
        }

        $id = $request->headers->get('user_id');
        if (null === $id) {
            throw new CustomUserMessageAuthenticationException('user_id not found');
        }

        $user = $this->userRepository->findOneBy(['user_id' => $id]);

        if (!($user instanceof User)) {
            throw new CustomUserMessageAuthenticationException('user not found');
        }

        $authorizationToken = explode(" ", $apiToken)[1];

        if ($authorizationToken != $user->getToken()) {
            throw new CustomUserMessageAuthenticationException('token incorrect');
        }

        if ($user->getTokenExpiresIn() < strtotime("now")) {
            throw new CustomUserMessageAuthenticationException('token expired');
        }

        return new SelfValidatingPassport(
            new UserBadge($id, function ($id) {
                return $this->userRepository->findOneBy(['user_id' => $id]);
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}

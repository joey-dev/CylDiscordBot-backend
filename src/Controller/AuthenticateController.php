<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\AcceptHeader;

class AuthenticateController extends AbstractController
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/authenticate/check', name: 'authenticate')]
    public function check(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!array_key_exists("code", $data)) {
            return new JsonResponse([
                "error" => "invalid_request",
                "error_description" => "no code in request",
            ]);
        }

        $result = $this->getAccessToken($data['code']);

        if ($result instanceof JsonResponse) {
            return $result;
        }

        $resultUser = $this->getUserFromDiscordApi($result["access_token"]);

        $user = $this->getAndSetUpdatedUser($doctrine, $resultUser, $result);

        return new JsonResponse([
            "data" => [
                "access_token" => $user->getToken(),
                "token_type" => $result["token_type"],
                "expires_in" => $result["expires_in"],
                "refresh_token" => $user->getRefreshToken(),
                "scope" => $result["scope"],
            ],
            "user" => [
                "id" => $user->getId(),
                "username" => $user->getUsername(),
                "token" => $user->getToken(),
                "user_id" => $user->getUserId()
            ]
        ]);
    }

    private function getAccessToken(string $code): array|JsonResponse {
        $url = 'https://discord.com/api/v8/oauth2/token';

        $options = [
            'headers' => [
                "Content-type" => " application/x-www-form-urlencoded",
            ],
            'body' => [
                "client_id" => $_ENV["discord_client_id"],
                "client_secret" => $_ENV["discord_client_secret"],
                "grant_type" => 'authorization_code',
                "redirect_uri" => 'http://localhost:3000/auth/redirect',
                "code" => $code,
            ],
        ];

        $response = $this->client->request(
            'POST',
            $url,
            $options
        );

        if ($response->getStatusCode() === 400) {
            return new JsonResponse([
                "status_code" => $response->getStatusCode(),
                "error" => "invalid_request",
                "error_description" => "Invalid item in request. (probably code)",
            ]);
        }

        $result = json_decode($response->getContent(), true);

        if ($result === FALSE || array_key_exists('error', $result)) {
            return new JsonResponse([
                "error" => "invalid_request",
                "error_description" => "Invalid code in request.",
            ]);
        }

        return $result;
    }

    private function getUserFromDiscordApi($access_token): array {
        $responseUser = $this->client->request(
            "GET",
            "https://discord.com/api/v8/users/@me",
            [
                'headers' => [
                    "Authorization" => "Bearer " . $access_token
                ],
            ]
        );

        return json_decode($responseUser->getContent(), true);
    }

    private function getAndSetUpdatedUser(ManagerRegistry $doctrine, $resultUser, $result): User {
        $entityManager = $doctrine->getManager();
        $user = $doctrine->getRepository(User::class)->findOneBy(['user_id' => $resultUser['id']]);

        if ($user instanceof User) {
            $user->setToken($result["access_token"]);
            $user->setUsername($resultUser['username']);
            $user->setRefreshToken($result['refresh_token']);
            $user->setTokenExpiresIn(strtotime("now") + $result['expires_in']);
        } else {
            $user = new User();
            $user->setUserId($resultUser['id']);
            $user->setToken($result["access_token"]);
            $user->setUsername($resultUser['username']);
            $user->setRefreshToken($result['refresh_token']);
            $user->setTokenExpiresIn(strtotime("now") + $result['expires_in']);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }
}

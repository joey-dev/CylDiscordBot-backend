<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Server;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ServerController extends AbstractController
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/user/servers', name: 'user_servers', methods: ["GET"])]
    public function getServers(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $userId = $request->headers->get('user-id');

        $servers = $this->getServersFromDiscordApi($request);

        $user = $this->getAndSetNewJoinedServersOnUser($userId, $doctrine, $servers);

        $availableServers = [];

        $alreadyJoinedServersIds = [];

        foreach ($user->getServer() as $server) {
            $alreadyJoinedServersIds[] = $server->getServerId();
        }

        foreach ($servers as $server) {
            if ($server['owner'] || $server['permissions'] == "2199023255551") {
                $server['alreadyJoined'] = in_array($server['id'], $alreadyJoinedServersIds);
                $availableServers[] = $server;
            }
        }

        return new JsonResponse([
            'servers' => $availableServers,
        ]);
    }

    #[Route('/user/server/{id}', name: 'user_server', methods: ["GET"])]
    public function getServerWithId(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $userId = $request->headers->get('user-id');

        $user = $doctrine->getRepository(User::class)->findOneBy(['user_id' => $userId]);

        $requestedServer = null;

        foreach ($user->getServer() as $server) {
            if ($server->getServerId() == $request->get("id")) {
                $requestedServer = $server;
            }
        }

        if ($requestedServer instanceof Server) {
            return new JsonResponse([
                "server" => [
                    "name" => $requestedServer->getName(),
                    "command_prefix" => $requestedServer->getCommandPrefix(),
                    "language" => [
                        "name" => $requestedServer->getLanguage()->getName(),
                        "small_name" => str_replace(
                            '-',
                            '',
                            $requestedServer->getLanguage()->getSmallName()
                        ),
                    ]
                ]
            ]);
        } else {
            return new JsonResponse([
                "error" => "server not found",
                "error_message" => "no server was found in the database for this user with that id"
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/user/server/{id}/roles', name: 'user_server_roles', methods: ["GET"])]
    public function getServerRolesWithId(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $userId = $request->headers->get('user-id');

        $user = $doctrine->getRepository(User::class)->findOneBy(['user_id' => $userId]);

        $requestedServer = null;

        foreach ($user->getServer() as $server) {
            if ($server->getServerId() == $request->get("id")) {
                $requestedServer = $server;
            }
        }

        if ($requestedServer instanceof Server) {
            $roles = [];

            foreach ($requestedServer->getRoles() as $role) {
                $roles[] = [
                    'id' => $role->getRoleId(),
                    'name' => $role->getName(),
                ];
            }

            return new JsonResponse([
                "roles" => $roles,
            ]);
        }
        return new JsonResponse([
            "error" => "server not found",
            "error_message" => "no server was found in the database for this user with that id"
        ], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/user/server/{id}/channels', name: 'user_server_channels', methods: ["GET"])]
    public function getServerChannelsWithId(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $userId = $request->headers->get('user-id');

        $user = $doctrine->getRepository(User::class)->findOneBy(['user_id' => $userId]);

        $requestedServer = null;

        foreach ($user->getServer() as $server) {
            if ($server->getServerId() == $request->get("id")) {
                $requestedServer = $server;
            }
        }

        if ($requestedServer instanceof Server) {
            $channels = [];

            foreach ($requestedServer->getChannels() as $channel) {
                $channels[] = [
                    'id' => $channel->getChannelId(),
                    'name' => $channel->getName(),
                ];
            }

            return new JsonResponse([
                "channels" => $channels,
            ]);
        }
        return new JsonResponse([
            "error" => "server not found",
            "error_message" => "no server was found in the database for this user with that id"
        ], Response::HTTP_BAD_REQUEST);
    }

    private function getServersFromDiscordApi(Request $request): array {
        $apiToken = $request->headers->get('Authorization');
        $authorizationToken = explode(" ", $apiToken)[1];

        $responseUser = $this->client->request(
            "GET",
            "https://discord.com/api/v8/users/@me/guilds",
            [
                'headers' => [
                    "Authorization" => "Bearer " . $authorizationToken
                ],
            ]
        );

        return json_decode($responseUser->getContent(), true);
    }

    private function getAndSetNewJoinedServersOnUser(string $userId, ManagerRegistry $doctrine, array $servers): User {
        $user = $doctrine->getRepository(User::class)->findOneBy(['user_id' => $userId]);
        $databaseServers = $doctrine->getRepository(Server::class)->findAll();

        if ($user instanceof User) {
            foreach ($servers as $server) {
                foreach ($databaseServers as $databaseServer) {
                    if ($server['id'] === $databaseServer->getServerId()) {
                        $serverFound = false;
                        foreach ($user->getServer() as $userServer) {
                            if ($userServer->getServerId() === $server['id']) {
                                $serverFound = true;
                            }
                        }
                        if (!$serverFound) {
                            $user->addServer($databaseServer);
                        }
                    }
                }
            }
            $entityManager = $doctrine->getManager();

            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $user;
    }
}

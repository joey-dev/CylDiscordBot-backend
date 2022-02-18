<?php

namespace App\Controller;

use App\Entity\Component;
use App\Entity\ComponentSettings;
use App\Entity\Module;
use App\Entity\Plugin;
use App\Entity\PluginSettings;
use App\Entity\Server;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/module', name: 'module_')]
class ModuleController extends AbstractController
{
    #[Route('/all/{serverId}', name: 'all_get', methods: ["GET"])]
    public function allGet(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $allConfiguredModules = $this->getAllConfiguredModules($request, $doctrine);

        if ($allConfiguredModules instanceof JsonResponse) {
            return $allConfiguredModules;
        }


        return new JsonResponse($allConfiguredModules, Response::HTTP_OK);
    }

    #[Route('/plugin/{serverId}', name: 'plugin_patch', methods: ["PATCH"])]
    public function pluginPatch(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $userId = $request->headers->get('user_id');
        $entityManager = $doctrine->getManager();

        if (!array_key_exists("plugin_id", $data) || !array_key_exists("checked", $data)) {
            return new JsonResponse([
                "error" => "invalid_request",
                "error_description" => "incorrect data in request",
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = $doctrine->getRepository(User::class)->findOneBy(['user_id' => $userId]);
        $requestedServer = null;

        foreach ($user->getServer() as $server) {
            if ($server->getServerId() == $request->get("serverId")) {
                $requestedServer = $server;
            }
        }

        $plugin = $doctrine->getRepository(Plugin::class)->find($data['plugin_id']);

        if (!$plugin || !$requestedServer) {
            return new JsonResponse([
                "error" => "invalid_request",
                "error_description" => "no plugin settings found with data",
            ], Response::HTTP_BAD_REQUEST);
        }

        $pluginSettings = $doctrine->getRepository(PluginSettings::class)
            ->findoneby(["plugin" => $plugin, "server" => $requestedServer]);

        $pluginSettings->setTurnedOn($data['checked']);
        $entityManager->flush();

        if (isset($data['return'])) {
            $allConfiguredModules = $this->getAllConfiguredModules($request, $doctrine);

            if ($allConfiguredModules instanceof JsonResponse) {
                return $allConfiguredModules;
            }

            return new JsonResponse($allConfiguredModules, Response::HTTP_OK);
        }

        return new JsonResponse([], Response::HTTP_OK);
    }

    #[Route('/component/{serverId}', name: 'component_patch', methods: ["PATCH"])]
    public function componentPatch(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $userId = $request->headers->get('user_id');
        $entityManager = $doctrine->getManager();

        if (!array_key_exists("component_id", $data) || !array_key_exists("checked", $data) || !array_key_exists("data", $data)) {
            return new JsonResponse([
                "error" => "invalid_request",
                "error_description" => "incorrect data in request",
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = $doctrine->getRepository(User::class)->findOneBy(['user_id' => $userId]);
        $requestedServer = null;

        foreach ($user->getServer() as $server) {
            if ($server->getServerId() == $request->get("serverId")) {
                $requestedServer = $server;
            }
        }

        $component = $doctrine->getRepository(Component::class)->find($data['component_id']);

        if (!$component || !$requestedServer) {
            return new JsonResponse([
                "error" => "invalid_request",
                "error_description" => "no module settings found with data",
            ], Response::HTTP_BAD_REQUEST);
        }

        $componentSettings = $doctrine->getRepository(ComponentSettings::class)
            ->findoneby(["component" => $component, "server" => $requestedServer]);

        $componentSettings->setTurnedOn($data['checked']);

        if ($data['data'] !== "{}") {
            if ($data['data'] === "empty") {
                $componentSettings->setData("{}");
            } else {
                $componentSettings->setData($data['data']);
            }
        }

        $entityManager->flush();

        if (isset($data['return'])) {
            $allConfiguredModules = $this->getAllConfiguredModules($request, $doctrine);

            if ($allConfiguredModules instanceof JsonResponse) {
                return $allConfiguredModules;
            }

            return new JsonResponse($allConfiguredModules, Response::HTTP_OK);
        }

        return new JsonResponse([], Response::HTTP_OK);
    }

    private function getAllConfiguredModules(Request $request, ManagerRegistry $doctrine): array|JsonResponse
    {
        $modules = $this->getAllModules($doctrine);
        $userId = $request->headers->get('user_id');

        $user = $doctrine->getRepository(User::class)->findOneBy(['user_id' => $userId]);
        $requestedServer = null;

        foreach ($user->getServer() as $server) {
            if ($server->getServerId() == $request->get("serverId")) {
                $requestedServer = $server;
            }
        }

        if (!$modules) {
            return new JsonResponse([
                "error" => "no modules",
                "error_message" => "could not find any modules",
            ], Response::HTTP_BAD_REQUEST);
        }

        $returnArray = [];

        foreach ($modules as $module) {
            $plugins = [];
            foreach ($module->getPlugins() as $plugin) {
                $components = [];
                foreach ($plugin->getComponents() as $component) {
                    $components[] = $this->turnComponentDataToArray($component, $doctrine, $requestedServer);
                }
                $plugins[] = $this->turnPluginDataToArray($plugin, $doctrine, $components, $requestedServer);
            }
            $returnArray[] = $this->turnModuleDataToArray($module, $plugins);
        }

        return $returnArray;
    }

    /**
     * @return Module[]
     */
    private function getAllModules(ManagerRegistry $doctrine): array
    {
        return $doctrine->getRepository(Module::class)->findBy([], ['order_id' => 'ASC']);
    }

    private function turnComponentDataToArray(Component $component, ManagerRegistry $doctrine, Server $server = null): array
    {
        $returnData = [
            "id" => $component->getId(),
            "name" => $component->getName(),
            "order_id" => $component->getOrderId(),
            "data" => $component->getData(),
            "type" => $component->getType()->getName(),
        ];

        if ($server) {
            $componentSettings = $doctrine->getRepository(ComponentSettings::class)
                ->findoneby(["component" => $component, "server" => $server]);

            $returnData['turned_on'] = (bool)$componentSettings->getTurnedOn();
            $returnData['server_data'] = $componentSettings->getData();
        }

        return $returnData;
    }

    private function turnPluginDataToArray(Plugin $plugin, ManagerRegistry $doctrine, array $components = null, Server $server = null): array
    {
        $returnData = [
            "id" => $plugin->getId(),
            "name" => $plugin->getName(),
            "order_id" => $plugin->getOrderId(),
        ];

        if ($server) {
            $pluginSettings = $doctrine->getRepository(PluginSettings::class)
                ->findoneby(["plugin" => $plugin, "server" => $server]);

            $returnData["turned_on"] = (bool)$pluginSettings->getTurnedOn();
        }

        if ($components) {
            $returnData["components"] = $components;
        }

        return $returnData;
    }

    private function turnModuleDataToArray(Module $module, array $plugins = null): array
    {
        $returnData = [
            "id" => $module->getId(),
            "name" => $module->getName(),
        ];

        if ($plugins) {
            $returnData["plugins"] = $plugins;
        }

        return $returnData;
    }
}

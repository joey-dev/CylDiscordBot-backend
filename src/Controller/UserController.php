<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'user', methods: ["GET"])]
    public function user(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $userRepository = $doctrine->getRepository(User::class);
        $userId = $request->headers->get('user-id');
        $user = $userRepository->findOneBy(['user_id' => $userId]);

        return new JsonResponse([
            "id" => $user->getId(),
            "username" => $user->getUsername(),
            "token" => $user->getToken(),
            "user_id" => $user->getUserId(),
        ]);
    }
}

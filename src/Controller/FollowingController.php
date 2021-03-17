<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class FollowingController extends AbstractController
{
    /**
     * @var UserRepo
     */
    private $userRepo;


    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    /**
     * @Route("/api/following/{id}", name="api_following")
     */
    public function follow($id)
    {
        $user = $this->userRepo->find($id);

        if (!$user) {
            return $this->json([
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $this->userRepo->addFollowing($this->getUser(), $user);

        return $this->json([
            'message' => 'user followed'
        ], Response::HTTP_OK);
    }
}

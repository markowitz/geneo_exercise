<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class FollowingController extends BaseController
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
     * @Route("/api/following/{user}", name="api_following")
     */
    public function follow(User $user)
    {
        try {
            $this->userRepo->addFollowing($this->getUser(), $user);
        }catch(\Exception $e) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, $e->getMessage());
        }


        return $this->json([
            'message' => 'user followed'
        ], Response::HTTP_OK);
    }
}

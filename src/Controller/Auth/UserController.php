<?php

namespace App\Controller\Auth;

use App\Entity\User;
use App\Exceptions\HttpException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/api/user/delete/{user}", name="api_user")
     */
    public function delete(User $user)
    {
        $this->denyAccessUnlessGranted(User::ADMIN);

        try {

            $this->userRepo->delete($user);

        } catch(Exception $e) {
            throw new HttpException($e->getMessage());
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);


    }
}

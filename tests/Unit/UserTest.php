<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserTest extends TestCase
{

    public function testUserCreate()
    {

        $user = new User();
        $user->setName('Ikechukwu Nwakanma');
        $user->setEmail('icnwakanma@gmail.com');
        $user->setPassword('geneotest'); //this should be an encoded string
        $this->assertEquals('Ikechukwu Nwakanma', $user->getName());
        $this->assertEquals('icnwakanma@gmail.com', $user->getEmail());
        $this->assertEquals('geneotest', $user->getPassword());

    }

}
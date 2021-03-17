<?php

namespace App\Tests\Unit;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{

      /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }


    public function testUserCreate()
    {

        $user = new User();
        $user->setName('Ikechukwu Nwakanma');
        $user->setEmail('icnwakanma@gmail.com');
        $user->setPassword('geneotest');

        $this->entityManager->persist($user);
        $this->entityManager->flush();


        $userRepo = $this->entityManager->getRepository(User::class)
                ->findOneBy(['email' => 'icnwakanma@gmail.com']);

        $this->assertEquals('Ikechukwu Nwakanma', $userRepo->getName());
        $this->assertEquals('icnwakanma@gmail.com', $userRepo->getEmail());

    }

    public function testUserDelete()
    {
        $user = new User();
        $user->setName('Ikechukwu Nwakanma');
        $user->setEmail('icnwakanma@gmail.com');
        $user->setPassword('geneotest');

        $this->entityManager->persist($user);
        $this->entityManager->flush();


        $userRepo = $this->entityManager->getRepository(User::class);

        $user = $userRepo->findOneBy(['email' => 'icnwakanma@gmail.com']);

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $userRepo = $userRepo->findOneBy(['email' => 'icnwakanma@gmail.com']);

        $this->assertNull($userRepo);

    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }

}
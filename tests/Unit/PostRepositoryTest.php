<?php

namespace App\Tests\Unit;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PostRepositoryTest extends KernelTestCase
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


    public function testPostCreate()
    {

        $post = new Post();
        $post->setTitle('Lorem Ipsum');
        $post->setContent('This is some lorem Ipsum Thing');

        $this->entityManager->persist($post);
        $this->entityManager->flush();


        $userRepo = $this->entityManager->getRepository(Post::class)
                ->findOneBy(['title' => 'Lorem Ipsum']);

        $this->assertEquals('Lorem Ipsum', $userRepo->getTitle());
        $this->assertEquals('This is some lorem Ipsum Thing', $userRepo->getContent());

    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }

}
<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testLogin()
    {

        $user = new User();

        $user->setName('Jane Doe');
        $user->setEmail('jane@gmail.com');
        $user->setPassword('$argon2id$v=19$m=65536,t=4,p=1$eTBDS3FZaURtcFJhbDNKbA$k60/BHW65f2Xg8x8yPFyEUXcnwnSkZc8A4UXv39KZU4');

        $entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $entityManager->persist($user);
        $entityManager->flush();

        $loginDetails = [
            'email' => 'jane@gmail.com',
            'password' => 'geneotest'
        ];

        $this->client->request('POST', '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($loginDetails)
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

    }

    public function testLoginInvalidCredentials()
    {

        $user = new User();

        $user->setName('Jane Doe');
        $user->setEmail('jane@gmail.com');
        $user->setPassword('$argon2id$v=19$m=65536,t=4,p=1$eTBDS3FZaURtcFJhbDNKbA$k60/BHW65f2Xg8x8yPFyEUXcnwnSkZc8A4UXv39KZU4');

        $entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $entityManager->persist($user);
        $entityManager->flush();

        $loginDetails = [
            'email' => 'jane@gmail.com',
            'password' => 'geneotest1'
        ];

        $this->client->request('POST', '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($loginDetails)
        );

        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());

    }

    public function testLoginInvalidContentTypeHeader()
    {

        $user = new User();

        $user->setName('Jane Doe');
        $user->setEmail('jane@gmail.com');
        $user->setPassword('$argon2id$v=19$m=65536,t=4,p=1$eTBDS3FZaURtcFJhbDNKbA$k60/BHW65f2Xg8x8yPFyEUXcnwnSkZc8A4UXv39KZU4');

        $entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $entityManager->persist($user);
        $entityManager->flush();

        $loginDetails = [
            'email' => 'jane@gmail.com',
            'password' => 'geneotest1'
        ];

        $this->client->request('POST', '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'multipart/form-data'],
            json_encode($loginDetails)
        );

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());

    }
}
<?php

namespace App\Tests\Functional;

use App\Entity\User;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseTestBundle extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    public $entityManager;

    protected function setUp(): void
    {

        $this->client = static::createClient();
        $this->faker = Factory::create();
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown(): void
    {
        self::ensureKernelShutdown();
        array_map('unlink', glob("uploads/images/*"));

    }


    protected function register($data = [])
    {
        $user = new User();

        $name = isset($data['name']) ? $data['name'] : 'Jane Doe';
        $email = isset($data['email']) ? $data['email'] : 'jane@gmail.com';
        $password = isset($data['password']) ? $data['password'] : '$argon2id$v=19$m=65536,t=4,p=1$eTBDS3FZaURtcFJhbDNKbA$k60/BHW65f2Xg8x8yPFyEUXcnwnSkZc8A4UXv39KZU4';
        $roles = isset($data['roles']) ? $data['roles'] : ['ROLE_USER'];

        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setRoles($roles);

        $entityManager = $this->entityManager;
        $entityManager->persist($user);
        $entityManager->flush();

        return $user;

    }

    protected function authorize($data = [])
    {
        $repo = $this->entityManager->getRepository(User::class);

        $email = isset($data['email']) ? $data['email'] : '';

        $postRepo = $repo->findOneBy(['email' => $email]);

        if(!$postRepo) {
           $postRepo = $this->register($data);
        }


        $user = [
                "email" => $postRepo->getEmail(),
                "password" => 'geneotest'
        ];

        $this->client->request('POST', '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($user)
        );

        $response = json_decode($this->client->getResponse()->getContent(), true);

        if (isset($response['jwt_token'])) {
            return [
                'HTTP_AUTHORIZATION' => "Bearer {$response['jwt_token']}",
                'CONTENT_TYPE' => 'application/json',
                ];
        }

        return null;
    }


    protected function postWithImageAndTagsApproved($headers)
    {

        $post = [
            "title" => $this->faker->name,
            "content" => $this->faker->sentence,
            "images" => base64_encode(file_get_contents($this->faker->imageUrl(640, 480, 'animals', true))),
            'tags' => 'lagos, benin'
        ];


        $this->client->request('POST', '/api/post',
            [],
            [],
            $headers,
            json_encode($post)
        );

        return json_decode($this->client->getResponse()->getContent(), true);

    }

}
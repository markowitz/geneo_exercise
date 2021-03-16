<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 3; $i++) {
            $user = new User();
            $user->setName($faker->name());
            $user->setEmail($faker->email());
            $user->setPassword($this->encoder->encodePassword($user, 'password'));
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
<?php

namespace App\Tests\Functional;

class UserTest extends BaseTestBundle
{
    public function testUserFollowing()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => '$argon2id$v=19$m=65536,t=4,p=1$eTBDS3FZaURtcFJhbDNKbA$k60/BHW65f2Xg8x8yPFyEUXcnwnSkZc8A4UXv39KZU4'
        ];

        $user = $this->register($data);

        $headers = $this->authorize();

        $this->client->request('POST', "follow/{$user->getId()}",
                [],
                [],
                $headers
        );


        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminCanDeleteUser()
    {
        $admin = [
            'name' => 'John Doe',
            'email' => 'j@gmail.com',
            'password' => '$argon2id$v=19$m=65536,t=4,p=1$eTBDS3FZaURtcFJhbDNKbA$k60/BHW65f2Xg8x8yPFyEUXcnwnSkZc8A4UXv39KZU4',
            'roles' => ['ROLE_ADMIN']
        ];

        $user = [
            'name' => 'Jane Doe',
            'email' => 'janedoe@gmail.com',
            'password' => '$argon2id$v=19$m=65536,t=4,p=1$eTBDS3FZaURtcFJhbDNKbA$k60/BHW65f2Xg8x8yPFyEUXcnwnSkZc8A4UXv39KZU4',
        ];

        $user = $this->register($user);

        $admin = $this->authorize($admin);

        $this->client->request('DELETE', "admin/user/{$user->getId()}",
                [],
                [],
                $admin
        );

        $this->assertResponseStatusCodeSame(204);

    }
}
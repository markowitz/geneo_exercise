<?php

namespace App\Tests\Controllers;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
class RegistrationControllerTest extends WebTestCase
{

    public function testRegister()
    {
        $client = static::createClient();

        $user = [
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => 'testing@12'
        ];

        $client->request('POST', '/api/register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($user)
        );

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertEquals('user registered successfully', $response['message']);
        $this->assertResponseIsSuccessful();

    }

    // public function testValidationErrors()
    // {
    //     $client = static::createClient();

    //     $user = [
    //         'name' => 'Jo',
    //         'email' => 'john@doe',
    //         'password' => 'testi'
    //     ];

    //     $client->request('POST', '/api/register',
    //         [],
    //         [],
    //         ['CONTENT_TYPE' => 'application/json'],
    //         json_encode($user)
    //     );

    //     $this->assertEquals(422, $client->getResponse()->getStatusCode());

    //     $response = json_decode($client->getResponse()->getContent(), true);

    //     $this->assertContains('This value is too short. It should have 3 characters or more.',
    //             $response['errors'],
    //             isset($response['errors']['name']) ?
    //             $response['errors']['name'] : ''
    //     );

    //     $this->assertContains('This value is not a valid email address.',
    //             $response['errors'],
    //             isset($response['errors']['email']) ?
    //             $response['errors']['email'] : ''
    //     );

    //     $this->assertContains('This value is too short. It should have 8 characters or more.',
    //             $response['errors'],
    //             isset($response['errors']['password']) ?
    //             $response['errors']['password'] : ''
    //     );

    // }
}
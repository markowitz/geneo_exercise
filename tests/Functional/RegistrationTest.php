<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegsitrationTest extends WebTestCase
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

    public function testValidationNameMustNotBeBlank()
    {
        $client = static::createClient();

        $user = [
            'name' => '',
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

        $this->assertEquals(422, $client->getResponse()->getStatusCode());
        $this->assertContains('This value should not be blank.', $response['errors']['name']);

    }

    public function testValidationNameLengthMustBeMinimumOfThree()
    {
        $client = static::createClient();

        $user = [
            'name' => 'jo',
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

        $this->assertEquals(422, $client->getResponse()->getStatusCode());
        $this->assertContains('This value is too short. It should have 3 characters or more.', $response['errors']['name']);

    }

    public function testValidationEmailMustNotBeBlank()
    {
        $client = static::createClient();

        $user = [
            'name' => 'John Doe',
            'email' => '',
            'password' => 'testing@12'
        ];

        $client->request('POST', '/api/register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($user)
        );

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(422, $client->getResponse()->getStatusCode());
        $this->assertContains('This value should not be blank.', $response['errors']['email']);

    }

    public function testValidationEmailMustBeValid()
    {
        $client = static::createClient();

        $user = [
            'name' => 'John Doe',
            'email' => 'johncom',
            'password' => 'testing@12'
        ];

        $client->request('POST', '/api/register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($user)
        );

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(422, $client->getResponse()->getStatusCode());
        $this->assertContains('This value is not a valid email address.', $response['errors']['email']);

    }


    public function testValidationPasswordMustBeMinimumOfEight()
    {
        $client = static::createClient();

        $user = [
            'name' => 'John Doe',
            'email' => 'john@gmail.com',
            'password' => 'testin'
        ];

        $client->request('POST', '/api/register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($user)
        );

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(422, $client->getResponse()->getStatusCode());
        $this->assertContains('This value is too short. It should have 8 characters or more.', $response['errors']['password']);

    }

    public function testValidationPasswordMustContainAtLeastANumber()
    {
        $client = static::createClient();

        $user = [
            'name' => 'John Doe',
            'email' => 'john@gmail.com',
            'password' => 'testing@'
        ];

        $client->request('POST', '/api/register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($user)
        );

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(422, $client->getResponse()->getStatusCode());
        $this->assertContains('password must contain at least a number', $response['errors']['password']);

    }


}
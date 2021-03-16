<?php

namespace App\Tests\Functional;

use App\Entity\Post;
use App\Entity\User;

class PostTest extends BaseTestBundle
{

    /**
     * test post create without images and tags
     */
    public function testCreateWithoutImagesAndTags()
    {
        $headers = $this->authorize();

        $post = [
                'title' => $this->faker->name,
                'content' => $this->faker->sentence
            ];

        $this->client->request('POST', 'post',
            [],
            [],
            $headers,
            json_encode($post)
        );

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

    }

    /**
     * test post create with images
     */
    public function testCreateWithImagesOnly()
    {
        $headers = $this->authorize();

        $post = [
            "title" => $this->faker->name,
            "content" => $this->faker->sentence,
            "images" => base64_encode(file_get_contents($this->faker->imageUrl(640, 480, 'animals', true)))
        ];


        $this->client->request('POST', 'post',
            [],
            [],
            $headers,
            json_encode($post)
        );

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
    }

    /**
     * test post create with images and tags
     */
    public function testCreatWithImagesAndTags()
    {
        $headers = $this->authorize();

        $post = [
            "title" => $this->faker->name,
            "content" => $this->faker->sentence,
            "images" => base64_encode(file_get_contents($this->faker->imageUrl(640, 480, 'animals', true))),
            'tags' => 'lagos, benin'
        ];


        $this->client->request('POST', 'post',
            [],
            [],
            $headers,
            json_encode($post)
        );

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
    }

    /**
     * title must not blank
     */
    public function testValidationTitleNotBlank()
    {
        $headers = $this->authorize();

        $post = [
            "title" => '',
            "content" => $this->faker->sentence,
            "images" => base64_encode(file_get_contents($this->faker->imageUrl(640, 480, 'animals', true))),
            'tags' => 'lagos, benin'
        ];


        $this->client->request('POST', 'post',
            [],
            [],
            $headers,
            json_encode($post)
        );

        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals(422, $this->client->getResponse()->getStatusCode());
        $this->assertContains('This value should not be blank.', $response['errors']['title']);
    }

    /**
     * title must have length minimum of three characters
     */
    public function testValidationTitleLengthMinimumOfThree()
    {
        $headers = $this->authorize();

        $post = [
            "title" => 'ab',
            "content" => $this->faker->sentence,
            "images" => base64_encode(file_get_contents($this->faker->imageUrl(640, 480, 'animals', true))),
            'tags' => 'lagos, benin'
        ];


        $this->client->request('POST', 'post',
            [],
            [],
            $headers,
            json_encode($post)
        );

        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals(422, $this->client->getResponse()->getStatusCode());
        $this->assertContains('This value is too short. It should have 3 characters or more.', $response['errors']['title']);
    }

    /**
     * test title not null validation
     */
    public function testValidationTitleNotNull()
    {
        $headers = $this->authorize();

        $post = [
            "title" => null,
            "content" => $this->faker->sentence,
            "images" => base64_encode(file_get_contents($this->faker->imageUrl(640, 480, 'animals', true))),
            'tags' => 'lagos, benin'
        ];


        $this->client->request('POST', 'post',
            [],
            [],
            $headers,
            json_encode($post)
        );

        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals(422, $this->client->getResponse()->getStatusCode());
        $this->assertContains('This value should not be null.', $response['errors']['title']);
    }

    /**
     * test content not blank validation
     */
    public function testValidationContentNotBlank()
    {
        $headers = $this->authorize();

        $post = [
            "title" => $this->faker->name,
            "content" => '',
            "images" => base64_encode(file_get_contents($this->faker->imageUrl(640, 480, 'animals', true))),
            'tags' => 'lagos, benin'
        ];


        $this->client->request('POST', 'post',
            [],
            [],
            $headers,
            json_encode($post)
        );

        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals(422, $this->client->getResponse()->getStatusCode());
        $this->assertContains('This value should not be blank.', $response['errors']['content']);
    }

    /**
     * test content length min 8 validation
     */
    public function testValidationContentLengthMinimumOfEight()
    {
        $headers = $this->authorize();

        $post = [
            "title" => $this->faker->name,
            "content" => 'ab came',
            "images" => base64_encode(file_get_contents($this->faker->imageUrl(640, 480, 'animals', true))),
            'tags' => 'lagos, benin'
        ];


        $this->client->request('POST', 'post',
            [],
            [],
            $headers,
            json_encode($post)
        );

        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals(422, $this->client->getResponse()->getStatusCode());
        $this->assertContains('This value is too short. It should have 8 characters or more.', $response['errors']['content']);
    }

    /**
     * test content should not be NotNull
     */
    public function testValidationContentNotNull()
    {
        $headers = $this->authorize();

        $post = [
            "title" => $this->faker->name,
            "content" => null,
            "images" => base64_encode(file_get_contents($this->faker->imageUrl(640, 480, 'animals', true))),
            'tags' => 'lagos, benin'
        ];


        $this->client->request('POST', 'post',
            [],
            [],
            $headers,
            json_encode($post)
        );

        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals(422, $this->client->getResponse()->getStatusCode());
        $this->assertContains('This value should not be null.', $response['errors']['content']);
    }

    /**
     * tags must be a string
     */
    public function testTagsMustBeAString()
    {
        $headers = $this->authorize();

        $post = [
            "title" => $this->faker->name,
            "content" => null,
            "images" => base64_encode(file_get_contents($this->faker->imageUrl(640, 480, 'animals', true))),
            'tags' => ['lagos, benin']
        ];


        $this->client->request('POST', 'post',
            [],
            [],
            $headers,
            json_encode($post)
        );

        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals(422, $this->client->getResponse()->getStatusCode());
        $this->assertContains('This value should be of type string.', $response['errors']['tags']);
    }

    /**
     * test to fetchApproved posts for auth user and followings
     */
    public function testFetchApproved()
    {
        $headers = $this->authorize();

        $post = $this->postWithImageAndTagsApproved($headers);

        $repo = $this->entityManager->getRepository(Post::class);

        $postRepo = $repo->findOneBy(['id' => $post['data']['id']]);

        $postRepo->setIsPublished(1);

        $this->entityManager->persist($postRepo);
        $this->entityManager->flush();

        $this->client->request('GET', 'posts',
        [],
        [],
        $headers);

        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(200);

        $this->assertCount(1, $response['data']);

    }

}
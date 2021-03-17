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

        $this->assertResponseStatusCodeSame(201);

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

        $this->assertResponseStatusCodeSame(201);

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

        $this->assertResponseStatusCodeSame(201);

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

        $this->assertResponseStatusCodeSame(422);

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

        $this->assertResponseStatusCodeSame(422);

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

        $this->assertResponseStatusCodeSame(422);

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

        $this->assertResponseStatusCodeSame(422);

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

        $this->assertResponseStatusCodeSame(422);

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

        $this->assertResponseStatusCodeSame(422);

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

        $this->assertResponseStatusCodeSame(422);

        $this->assertContains('This value should be of type string.', $response['errors']['tags']);

    }

    /**
     * test to show approved posts for auth user and followings
     */
    public function testToShowApprovedPosts()
    {
        $headers = $this->authorize();

        $post = $this->postWithImageAndTagsApproved($headers);

        $repo = $this->entityManager->getRepository(Post::class);

        $postRepo = $repo->findOneBy(['id' => $post['data']['id']]);

        $postRepo->setApproved(1);

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

     /**
     * test to fetchApproved posts for auth user and followings
     */
    public function testUserCannotSeeListOfPostsUnlessPublished()
    {
        $headers = $this->authorize();

        $post = $this->postWithImageAndTagsApproved($headers);

        $repo = $this->entityManager->getRepository(Post::class);

        $postRepo = $repo->findOneBy(['id' => $post['data']['id']]);

        $this->entityManager->persist($postRepo);
        $this->entityManager->flush();

        $this->client->request('GET', 'posts',
        [],
        [],
        $headers);

        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(200);

        $this->assertCount(0, $response['data']);

    }

    /**
     * users can see posts of users they follow
     */
    public function testUserCanSeePostsOfUsersTheyFollow()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => '$argon2id$v=19$m=65536,t=4,p=1$eTBDS3FZaURtcFJhbDNKbA$k60/BHW65f2Xg8x8yPFyEUXcnwnSkZc8A4UXv39KZU4'
        ];

        $user = $this->register($data);

        $userFollow = $this->authorize();

        $this->client->request('POST', "following/{$user->getId()}",
                [],
                [],
                $userFollow
        );


        $postAuthor = $this->authorize($data);

        $post = $this->postWithImageAndTagsApproved($postAuthor);

        $repo = $this->entityManager->getRepository(Post::class);

        $postRepo = $repo->findOneBy(['id' => $post['data']['id']]);

        $postRepo->setApproved(1);

        $this->entityManager->persist($postRepo);
        $this->entityManager->flush();

        $this->client->request('GET', 'posts',
        [],
        [],
        $userFollow);

        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(200);

        $this->assertCount(1, $response['data']);

    }

    /**
     * author can view single published posts
     */
    public function testAuthorCanViewSinglePublishedPost()
    {
        $header = $this->authorize();

        $post = $this->postWithImageAndTagsApproved($header);

        $repo = $this->entityManager->getRepository(Post::class);

        $postRepo = $repo->findOneBy(['id' => $post['data']['id']]);

        $postRepo->setApproved(1);

        $this->entityManager->persist($postRepo);
        $this->entityManager->flush();

        $this->client->request('GET', "post/{$postRepo->getSlug()}",
        [],
        [],
        $header);

        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(200);

        $this->assertArrayHasKey("title", $response['data']);

    }

    /**
     * author can view single unpublished post
     */
    public function testAuthorCanViewSingleUnpublishedPost()
    {
        $header = $this->authorize();

        $post = $this->postWithImageAndTagsApproved($header);

        $repo = $this->entityManager->getRepository(Post::class);

        $postRepo = $repo->findOneBy(['id' => $post['data']['id']]);

        $this->entityManager->persist($postRepo);
        $this->entityManager->flush();

        $this->client->request('GET', "post/{$postRepo->getSlug()}",
        [],
        [],
        $header);

        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(200);

        $this->assertArrayHasKey("title", $response['data']);

    }

    /**
     * user cannot view single post if not author
     */
    public function testUserCannotViewSingleUnpublishedPostIfNotAuthor()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => '$argon2id$v=19$m=65536,t=4,p=1$eTBDS3FZaURtcFJhbDNKbA$k60/BHW65f2Xg8x8yPFyEUXcnwnSkZc8A4UXv39KZU4'
        ];

        $postAuthor = $this->authorize($data);

        $post = $this->postWithImageAndTagsApproved($postAuthor);

        $repo = $this->entityManager->getRepository(Post::class);

        $postRepo = $repo->findOneBy(['id' => $post['data']['id']]);

        $this->entityManager->persist($postRepo);
        $this->entityManager->flush();

        $authUser = $this->authorize();

        $this->client->request('GET', "post/{$postRepo->getSlug()}",
        [],
        [],
        $authUser);

        $this->assertResponseStatusCodeSame(403);

    }

    /**
     * user cannot view single published post if not following user
     */
    public function testUserCannotViewSinglePublishedPostIfNotFollowingAuthor()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => '$argon2id$v=19$m=65536,t=4,p=1$eTBDS3FZaURtcFJhbDNKbA$k60/BHW65f2Xg8x8yPFyEUXcnwnSkZc8A4UXv39KZU4'
        ];

        $postAuthor = $this->authorize($data);

        $post = $this->postWithImageAndTagsApproved($postAuthor);

        $repo = $this->entityManager->getRepository(Post::class);

        $postRepo = $repo->findOneBy(['id' => $post['data']['id']]);

        $postRepo->setApproved(1);

        $this->entityManager->persist($postRepo);
        $this->entityManager->flush();

        $authUser = $this->authorize();

        $this->client->request('GET', "post/{$postRepo->getSlug()}",
        [],
        [],
        $authUser);

        $this->assertResponseStatusCodeSame(403);

    }

    /**
     * test user can view single published post of users they floow
     */
    public function testUserCanViewSinglePublishedPostOfUsersTheyFollow()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => '$argon2id$v=19$m=65536,t=4,p=1$eTBDS3FZaURtcFJhbDNKbA$k60/BHW65f2Xg8x8yPFyEUXcnwnSkZc8A4UXv39KZU4'
        ];

        $user = $this->register($data);

        $follower = $this->authorize();

        $this->client->request('POST', "following/{$user->getId()}",
                [],
                [],
                $follower
        );

        $postAuthor = $this->authorize($data);

        $post = $this->postWithImageAndTagsApproved($postAuthor);

        $repo = $this->entityManager->getRepository(Post::class);

        $postRepo = $repo->findOneBy(['id' => $post['data']['id']]);

        $postRepo->setApproved(1);

        $this->entityManager->persist($postRepo);
        $this->entityManager->flush();

        $this->client->request('GET', "post/{$postRepo->getSlug()}",
        [],
        [],
        $follower);

        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(200);

        $this->assertArrayHasKey("title", $response['data']);

    }

    /**
     * test admin can fetch all pending posts
     */
    public function testAdminCanFetchAllPendingPosts()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => '$argon2id$v=19$m=65536,t=4,p=1$eTBDS3FZaURtcFJhbDNKbA$k60/BHW65f2Xg8x8yPFyEUXcnwnSkZc8A4UXv39KZU4',
            'roles' => ['ROLE_ADMIN']
        ];


        $admin = $this->authorize($data);

        $user = $this->authorize();

        $this->postWithImageAndTagsApproved($user);

        $this->client->request('GET', '/api/admin/pending-posts',
        [],
        [],
        $admin);

        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseStatusCodeSame(200);

        $this->assertGreaterThan(0, $response['data']);

    }

    /**
     * author can delete post
     */
    public function testAuthorCanDeletePost()
    {
        $header = $this->authorize();

        $post = $this->postWithImageAndTagsApproved($header);

        $this->client->request("DELETE", "post/{$post['data']['id']}",
        [],
        [],
        $header);

        $this->assertResponseStatusCodeSame(204);

    }

    /**
     * user cannot delete post if not owner
     */
    public function testUserCannotDeletePostIfNotOwner()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => '$argon2id$v=19$m=65536,t=4,p=1$eTBDS3FZaURtcFJhbDNKbA$k60/BHW65f2Xg8x8yPFyEUXcnwnSkZc8A4UXv39KZU4'
        ];

        $this->register($data);

        $authUser = $this->authorize();

        $postAuthor = $this->authorize($data);

        $post = $this->postWithImageAndTagsApproved($postAuthor);

        $this->client->request('DELETE', "post/{$post['data']['id']}",
        [],
        [],
        $authUser);

        $this->assertResponseStatusCodeSame(403);
    }

    /**
     * admin can delete post
     */
    public function testAdminCanDeletePost()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => '$argon2id$v=19$m=65536,t=4,p=1$eTBDS3FZaURtcFJhbDNKbA$k60/BHW65f2Xg8x8yPFyEUXcnwnSkZc8A4UXv39KZU4',
            'roles' => ['ROLE_ADMIN']
        ];


        $admin = $this->authorize($data);

        $user = $this->authorize();

        $post = $this->postWithImageAndTagsApproved($user);

        $this->client->request('DELETE', "post/{$post['data']['id']}",
        [],
        [],
        $admin);

        $this->assertResponseStatusCodeSame(204);
    }

    /**
     * admin can approve post
     */
    public function testAdminCanApprovePost()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => '$argon2id$v=19$m=65536,t=4,p=1$eTBDS3FZaURtcFJhbDNKbA$k60/BHW65f2Xg8x8yPFyEUXcnwnSkZc8A4UXv39KZU4',
            'roles' => ['ROLE_ADMIN']
        ];


        $admin = $this->authorize($data);

        $user = $this->authorize();

        $post = $this->postWithImageAndTagsApproved($user);

        $approve = [ 'approved' => 1];

        $this->client->request('POST', "admin/post/{$post['data']['id']}/approval",
        [],
        [],
        $admin,
        json_encode($approve));

        $this->assertResponseStatusCodeSame(200);

    }

}
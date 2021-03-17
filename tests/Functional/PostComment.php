<?php

namespace App\Functional;

use App\Entity\Post;
use App\Tests\Functional\BaseTestBundle;

class PostComment extends BaseTestBundle
{

    public function testUserCanCommentOnPostOfUsersTheyFollow()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => '$argon2id$v=19$m=65536,t=4,p=1$eTBDS3FZaURtcFJhbDNKbA$k60/BHW65f2Xg8x8yPFyEUXcnwnSkZc8A4UXv39KZU4'
        ];

        $user = $this->register($data);

        $followerToken = $this->authorize();

        $this->client->request('POST', "following/{$user->getId()}",
                [],
                [],
                $followerToken
        );

        $postAuthorToken = $this->authorize($data);

        $post = $this->postWithImageAndTagsApproved($postAuthorToken);

        $repo = $this->entityManager->getRepository(Post::class);

        $postRepo = $repo->findOneBy(['id' => $post['data']['id']]);

        $postRepo->setApproved(1);

        $this->entityManager->persist($postRepo);
        $this->entityManager->flush();



        $comment = [
            'comment' => 'this is a test comment'
        ];

        $this->client->request('POST', "post/{$postRepo->getId()}/comment",
            [],
            [],
            $followerToken,
            json_encode($comment)
        );

        $this->assertResponseStatusCodeSame(201);

    }
}
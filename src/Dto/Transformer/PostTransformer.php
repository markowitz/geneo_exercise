<?php

namespace App\Dto\Transformer;

use App\Dto\PostRequest;
use App\Entity\Post;

class PostTransformer extends AbstractTransformer
{

    /**
     * @param Post $post
     *
     * @return PostRequest
     */
    public function transformFromObject($post)
    {
        $dto = new PostRequest();

        $userTransformer    = new UserTransformer();
        $imageTransformer   = new ImageTransformer();
        $tagTransformer     = new TagTransformer();
        $postCommentTransformer = new PostCommentTransformer();

        $dto->id           = $post->getId();
        $dto->title        = $post->getTitle();
        $dto->content      = $post->getContent();
        $dto->author       = $userTransformer->transformFromObject($post->getAuthor());
        $dto->images       = $imageTransformer->transformFromObject($post->getImages());
        $dto->tags         = $tagTransformer->transformFromObjects($post->getTags());
        $dto->comments     = $postCommentTransformer->transformFromObjects($post->getPostComments());
        $dto->slug         = $post->getSlug();
        $dto->approved     = $post->getApproved();
        $dto->createdAt    = $post->getCreatedAt();
        $dto->updatedAt    = $post->getUpdatedAt();


        return $dto;
    }

}
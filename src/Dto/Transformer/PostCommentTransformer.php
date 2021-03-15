<?php

namespace App\Dto\Transformer;

use App\Entity\Post;
use App\Entity\PostComment;
use App\Dto\PostCommentRequest;
use App\Entity\User;

class PostCommentTransformer extends AbstractTransformer
{

    /**
     * @param PostComment $postComment
     *
     * @return PostCommentDto
     */
    public function transformFromObject($postComment)
    {
        $dto = new PostCommentRequest();

        $dto->comment = $postComment->getComment();
        $dto->user    = $postComment->getUser()->getName();

        return $dto;
    }

}
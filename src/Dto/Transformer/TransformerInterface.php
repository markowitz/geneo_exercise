<?php

namespace App\Dto\Transformer;

interface TransformerInterface
{
    public function transformFromObject($object);
    public function transformFromObjects(iterable $objects): iterable;
}
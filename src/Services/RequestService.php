<?php

namespace App\Services;

use Symfony\Component\Serializer\SerializerInterface;

class RequestService
{

     /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param Request $request
     * @param Object $className
     * @return Object $dto
     */
    public function mapContent($request, $className)
    {
        $dto = $this->serializer->deserialize(
                $request,
                $className,
                'json'
        );

        return $dto;
    }

}
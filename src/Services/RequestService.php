<?php

namespace App\Services;

use Symfony\Component\Serializer\SerializerInterface;

class RequestService
{

     /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var ImageService
     */
    private $imageService;

    public function __construct(SerializerInterface $serializer, ImageService $imageService)
    {
        $this->serializer = $serializer;
        $this->imageService = $imageService;
    }

    /**
     * @param Request $request
     * @param Object $className
     */
    public function mapContent($request, $className)
    {

        $dto = $this->serializer->deserialize(
                json_encode($request->request->all()),
                $className,
                'json'
        );

        return $dto;

    }

    public function mapRequestToFiles($content, $className)
    {

        $dto = $this->handleFileRequest($content);

        $dtos = [];

        array_map(function($dto) use (&$dtos, $className) {
            $dtos[] = $this->serializer->deserialize(
                        json_encode($dto),
                        $className,
                        'json'
                        );
        }, $dto);

        return $dtos;

    }

    protected function handleFileRequest($request)
    {
        $dto = [];

        if (!is_array($request)) {
            $dto[] = $this->imageService->handleUpload($request);

        } else {
            foreach($request as $req) {
                $dto[] = $this->imageService->handleUpload($req);
             }
        }

        return $dto;
    }

}
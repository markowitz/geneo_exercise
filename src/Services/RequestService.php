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
     * @return Object $dto
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

    /**
     * map request to files
     * @param String | Array $content
     * @param String $className
     * @return Array $dtos
     */
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

    /**
     * handles file request and upload
     * @param String | Array $request
     * @return Array $dto
     */
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
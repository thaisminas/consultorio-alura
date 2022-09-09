<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseFactory
{
    /**
     * @var bool
     */
    private $success;


    /**
     * @var int
     */
    private $currentPage;

    /**
     * @var int
     */
    private $itemPerPage;


    private $contentResponse;


    /**
     * @var int
     */
    private $statusResponse;


    public function __construct(
        bool $success,
        $contentResponse,
        int $statusResponse = Response::HTTP_OK,
        int $currentPage = null,
        int $itemPerPage = null
    )
    {
        $this->success = $success;
        $this->contentResponse = $contentResponse;
        $this->statusResponse = $statusResponse;
        $this->currentPage = $currentPage;
        $this->itemPerPage = $itemPerPage;
    }

    public function getResponse(): JsonResponse
    {
        $contentResponse = [
            'success' => $this->success,
            'currentPage' => $this->currentPage,
            'itemPerPage' => $this->itemPerPage,
            'contentResponse' => $this->contentResponse
        ];

        if(is_null($this->currentPage)){
            unset($contentResponse['currentPage']);
            unset($contentResponse['itemPerPage']);
        }

        return new JsonResponse($contentResponse, $this->statusResponse);
    }
}
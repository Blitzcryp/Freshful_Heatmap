<?php


namespace App\Http\Controllers;

use App\Services\PostbackService;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostbackController
{
    private Request $request;
    private ResponseFactory $response;
    private PostbackService $postbackService;

    public function __construct(Request $request, ResponseFactory $response, PostbackService $postbackService)
    {
        $this->request = $request;
        $this->response = $response;
        $this->postbackService = $postbackService;

    }

    public function __invoke(): JsonResponse
    {
        $result = $this->postbackService->postback($this->request->json()->all());

        return $this->response->json(["success" => $result]);
    }
}

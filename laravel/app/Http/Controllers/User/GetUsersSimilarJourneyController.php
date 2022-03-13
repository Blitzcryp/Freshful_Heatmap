<?php

namespace App\Http\Controllers\User;

use App\Services\User\GetUsersSimilarJourneyService;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

class GetUsersSimilarJourneyController
{
    private ResponseFactory $response;
    private GetUsersSimilarJourneyService $getUsersSimilarJourneyService;

    public function __construct(ResponseFactory $response, GetUsersSimilarJourneyService $getUsersSimilarJourneyService)
    {
        $this->response = $response;
        $this->getUsersSimilarJourneyService = $getUsersSimilarJourneyService;
    }

    public function __invoke(int $id): JsonResponse
    {
        $result = $this->getUsersSimilarJourneyService->get($id);

        return $this->response->json($result);
    }
}

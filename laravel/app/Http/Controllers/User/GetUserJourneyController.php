<?php

namespace App\Http\Controllers\User;

use App\Services\User\GetUserJourneyService;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

class GetUserJourneyController
{
    private ResponseFactory $responseFactory;
    private GetUserJourneyService $getUserJourneyService;

    public function __construct(ResponseFactory $responseFactory, GetUserJourneyService $getUserJourneyService)
    {
        $this->responseFactory = $responseFactory;
        $this->getUserJourneyService = $getUserJourneyService;
    }

    public function __invoke(int $id): JsonResponse
    {
        $result =  $this->getUserJourneyService->get($id);

        return $this->responseFactory->json($result);
    }
}

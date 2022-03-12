<?php

namespace App\Http\Controllers\User;

use App\Services\User\GetUserJourneyService;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class GetUserJourneyController
{
    private Request $request;
    private ResponseFactory $responseFactory;
    private GetUserJourneyService $getUserJourneyService;

    public function __construct(Request $request, ResponseFactory $responseFactory, GetUserJourneyService $getUserJourneyService)
    {
        $this->request = $request;
        $this->responseFactory = $responseFactory;
        $this->getUserJourneyService = $getUserJourneyService;
    }

    public function __invoke(int $id): Collection
    {
        return $this->getUserJourneyService->get($id);
    }
}

<?php


namespace App\Http\Controllers\Statistics;


use App\Services\Statistics\LinkHitsService;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LinkHitsController
{
    private Request $request;
    private ResponseFactory $response;
    private LinkHitsService $linkHitsService;

    public function __construct(Request $request, ResponseFactory $response, LinkHitsService $linkHitsService)
    {
        $this->request = $request;
        $this->response = $response;
        $this->linkHitsService = $linkHitsService;
    }

    public function __invoke(): JsonResponse
    {
        $result = $this->linkHitsService->getStats(
            [
                "link" => $this->request->input("link"),
                "startDateTime" => $this->request->input("startDateTime"),
                "endDateTime" => $this->request->input("endDateTime")
            ]
        );

        return $this->response->json(["hits" => $result]);
    }
}

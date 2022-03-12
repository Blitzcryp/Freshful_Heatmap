<?php

namespace App\Http\Controllers\Statistics;

use App\Services\Statistics\LinkTypeHitsService;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;

class LinkTypeHitsController
{
    private Request $request;
    private ResponseFactory $response;
    private LinkTypeHitsService $linkTypeHitsService;

    public function __construct(Request $request, ResponseFactory $response, LinkTypeHitsService $linkTypeHitsService)
    {
        $this->request = $request;
        $this->response = $response;
        $this->linkTypeHitsService = $linkTypeHitsService;
    }

    public function __invoke()
    {
        $this->linkTypeHitsService->get(
            [
                "startDateTime" => $this->request->input("startDateTime"),
                "endDateTime" => $this->request->input("endDateTime")
            ]
        );
    }
}

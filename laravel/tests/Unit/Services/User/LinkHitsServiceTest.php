<?php

namespace Services\User;

use App\Repositories\Statistics\LinkHitsRepository;
use App\Services\Statistics\LinkHitsService;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class LinkHitsServiceTest extends TestCase
{
    public function test_it_works(){

        $linkHitsRepository = $this->mock(LinkHitsRepository::class);
        $linkHitsRepository->shouldReceive("get")
            ->with("link", "startDateTime", "endDateTime")
            ->andReturn(10);

        /** @var LinkHitsService $linkHitsService */
        $linkHitsService = $this->app->get(LinkHitsService::class);

        $result = $linkHitsService->getStats(["link" => "link", "startDateTime" => "startDateTime", "endDateTime" => "endDateTime"]);

        $this->assertEquals(10, $result);
    }

    /**
     * @dataProvider validationData
     */
    public function test_validator_works(array $data){
        ["input" => $input, "error" => $error] = $data;

        /** @var LinkHitsService $linkHitsService */
        $linkHitsService = $this->app->get(LinkHitsService::class);

        try {
            $linkHitsService->getStats($input);
        } catch (ValidationException $e){
            $this->assertEquals($error, $e->validator->errors()->messages());
        }
    }

    public function validationData(): array
    {
        return [
            "test #1 - missing link" => [
                [
                    "input" => [
                        "startDateTime" => "startDateTime",
                        "endDateTime" => "endDateTime"
                    ],
                    "error" => ["link" => ["required"]]
                ]
            ],
            "test #2 - missing startDateTime" => [
                [
                    "input" => [
                        "link" => "link",
                        "endDateTime" => "endDateTime"
                    ],
                    "error" => ["startDateTime" => ["required"]]
                ]
            ],
            "test #3 - missing endDateTime" => [
                [
                    "input" => [
                        "link" => "link",
                        "startDateTime" => "startDateTime",
                    ],
                    "error" => ["endDateTime" => ["required"]]
                ]
            ],
            "test #4 - missing everything" => [
                [
                    "input" => [],
                    "error" => [
                        "link" => ["required"],
                        "startDateTime" => ["required"],
                        "endDateTime" => ["required"]
                    ]
                ]
            ]
        ];
    }
}

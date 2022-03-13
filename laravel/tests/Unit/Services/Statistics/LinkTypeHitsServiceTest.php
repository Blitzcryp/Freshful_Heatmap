<?php

namespace Services\User;

use App\Repositories\Statistics\LinkTypesHitsRepository;
use App\Services\Statistics\LinkTypeHitsService;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class LinkTypeHitsServiceTest extends TestCase
{
    public function test_it_works(){

        $linkTypeHitsRepository = $this->mock(LinkTypesHitsRepository::class);
        $linkTypeHitsRepository->shouldReceive("getAllRecordsInTimeInterval")
            ->with("2020-01-30 05:34:05", '2020-01-30 05:44:03')
            ->andReturn(
                collect([
                    (object) ["linkType" => "product"],
                    (object) ["linkType" => "homepage"],
                ])
            );

        /** @var LinkTypeHitsService $linkTypeHitsService */
        $linkTypeHitsService = $this->app->get(LinkTypeHitsService::class);

        $result = $linkTypeHitsService->get(
            [
                "startDateTime" => "2020-01-30 05:34:05",
                "endDateTime" => "2020-01-30 05:44:03"
            ]
        );

        $this->assertEquals([
            "product" => 1,
            "category" => 0,
            "static-page" => 0,
            "checkout" => 0,
            "homepage" => 1
        ], $result);
    }

    /**
     * @dataProvider validationData
     */
    public function test_validator_works(array $data){
        ["input" => $input, "errors" => $errors] = $data;

        /** @var LinkTypeHitsService $linkTypeHitsService */
        $linkTypeHitsService = $this->app->get(LinkTypeHitsService::class);

        try {
            $linkTypeHitsService->get($input);
        } catch (ValidationException $e){
            $this->assertEquals($errors, $e->validator->errors()->messages());
        }
    }

    public function validationData(): array
    {
        return [
            "test #1 - start and end date are missing" => [
                [
                    "input" => [],
                    "errors" => [
                        "startDateTime" => ["required"],
                        "endDateTime" => ["required"]
                    ]
                ]
            ],
            "test #2 - start and end date are not strings" => [
                [
                    "input" => [
                        "startDateTime" => 123,
                        "endDateTime" => 234,
                    ],
                    "errors" => [
                        "startDateTime" => ["string", "date_format:Y-m-d H:i:s"],
                        "endDateTime" => ["string", "date_format:Y-m-d H:i:s"]
                    ]
                ]
            ],
            "test #3 - start and end date time are wrong format" => [
                [
                    "input" => [
                        "startDateTime" => "2020",
                        "endDateTime" => "2021",
                    ],
                    "errors" => [
                        "startDateTime" => ["date_format:Y-m-d H:i:s"],
                        "endDateTime" => ["date_format:Y-m-d H:i:s"]
                    ]
                ]
            ]
        ];
    }
}

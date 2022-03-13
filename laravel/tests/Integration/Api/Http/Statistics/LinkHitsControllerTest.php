<?php

namespace Tests\Integration\Api\Http\Statistics;

use App\Enums\LinksTypeEnums;
use Carbon\Carbon;
use Tests\DbTestCase;

class LinkHitsControllerTest extends DbTestCase
{
    public function test_it_works(){
        ["id" => $firstUserId] = $this->createUser();
        ["id" => $secondUserId] = $this->createUser(["email" => "test@gmail.com"]);

        $insertData = [];
        foreach(LinksTypeEnums::cases() as $linkType){
            $insertData[] = [
                "uid" => $firstUserId,
                "link" => "test" . $linkType,
                "linkType" => $linkType,
                "timestamp" => Carbon::now()->toDateTimeString()
            ];

            $insertData[] = [
                "uid" => $secondUserId,
                "link" => "test" . $linkType,
                "linkType" => $linkType,
                "timestamp" => Carbon::now()->toDateTimeString()
            ];
        }

        $this->getDatabaseManager()->connection()->table("rollup")->insert($insertData);


        $response = $this->getJson("/api/statistics/link-type-hits?startDateTime=2000-01-25 12:03:25&endDateTime=2023-01-25 13:03:25");

        $response->assertStatus(200);

        $response->assertJson(
             [
                "product" => 2,
                "category" => 2,
                "static-page" => 2,
                "checkout" => 2,
                "homepage" => 2
            ]
        );
    }

    /**
     * @dataProvider validationData
     */
    public function test_validator_works(array $data){
        ["startDateTime" => $startDateTime, "endDateTime" => $endDateTime, "status" => $status, "errors" => $errors] = $data;

        $response = $this->getJson("/api/statistics/link-type-hits?startDateTime=". $startDateTime ."&endDateTime=" . $endDateTime);

        $response->assertStatus($status);

        $response->assertJson(["errors" => $errors]);
    }

    public function validationData(): array
    {
        return [
            "test #1 - startDateTime and endDateTime are missing" => [
                [
                    "startDateTime" => null,
                    "endDateTime" => null,
                    "status" => 422,
                    "errors" => [
                        "startDateTime" => ["required"],
                        "endDateTime" => ["required"]
                    ]
                ]
            ],
            "test #2 - startDateTime and endDateTime are not date format" => [
                [
                    "startDateTime" => "wrong",
                    "endDateTime" => "wrong",
                    "status" => 422,
                    "errors" => [
                        "startDateTime" => ["date_format:Y-m-d H:i:s"],
                        "endDateTime" => ["date_format:Y-m-d H:i:s"]
                    ]
                ]
            ]
        ];
    }
}

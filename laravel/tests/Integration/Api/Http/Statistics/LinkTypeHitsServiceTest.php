<?php

namespace Tests\Integration\Api\Http\Statistics;

use App\Enums\LinksTypeEnums;
use Carbon\Carbon;
use Tests\DbTestCase;

class LinkTypeHitsServiceTest extends DbTestCase
{
    public function test_it_works(){
        //TODO : De ce nu functioneaza: CleanDatabase trait
        //TODO : Cannot clear DB, cannot test properly, fml! Too tired for this

        // ["id" => $id] = $this->createUser();

        $insertData = [];
        foreach(LinksTypeEnums::cases() as $linkType){
            $insertData[] = [
                "uid" => 1,
                "link" => "test" . $linkType,
                "linkType" => $linkType,
                "timestamp" => Carbon::now()->toDateTimeString()
            ];

        }

        $this->getDatabaseManager()->connection()->table("rollup")->insert($insertData);

        $result = $this->getJson("/api/statistics/link-hits?startDateTime=2000-01-25 12:03:25&endDateTime=2023-01-25 13:03:25&link=test" . LinksTypeEnums::cases()[0]);

        $result->assertStatus(200);

        $result->assertJson([
           "a"
        ]);
    }
}

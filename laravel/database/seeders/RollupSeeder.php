<?php

namespace Database\Seeders;

use App\Database\Seeder;
use App\Enums\LinksTypeEnums;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RollupSeeder extends Seeder
{
    public function run()
    {
        $insertData = collect([]);

        foreach([LinksTypeEnums::HOMEPAGE, LinksTypeEnums::STATIC_PAGE, LinksTypeEnums::PRODUCT] as $linkType){
            $insertData->push([
                "uid" => 3,
                "link" => "random-link-for-" . $linkType,
                "linkType" => $linkType,
                "timestamp" => Carbon::now()->toDateTimeString()
            ]);
            $insertData->push([
                "uid" => 4,
                "link" => "random-link-for-" . $linkType,
                "linkType" => $linkType,
                "timestamp" => Carbon::now()->toDateTimeString()
            ]);
        }

        foreach(LinksTypeEnums::cases() as $linkType){
            $insertData->push([
                "uid" => 1,
                "link" => "random-link-for-" . $linkType,
                "linkType" => $linkType,
                "timestamp" => Carbon::now()->toDateTimeString()
            ]);
            $insertData->push([
                "uid" => 2,
                "link" => "random-link-for-" . $linkType,
                "linkType" => $linkType,
                "timestamp" => Carbon::now()->toDateTimeString()
            ]);
            $insertData->push([
                "uid" => 5,
                "link" => "random-link-for-" . $linkType,
                "linkType" => $linkType,
                "timestamp" => Carbon::now()->toDateTimeString()
            ]);
        }

        $this->databaseManager
            ->connection()
            ->table("rollup")
            ->insert($insertData->toArray());
    }
}

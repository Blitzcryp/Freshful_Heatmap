<?php

namespace App\Services\Statistics;

use App\Enums\LinksTypeEnums;
use App\Repositories\Statistics\LinkTypesHitsRepository;
use Illuminate\Contracts\Validation\Factory;

class LinkTypeHitsService
{
    private LinkTypesHitsRepository $linkTypesHitsRepository;
    private Factory $validator;

    public function __construct(LinkTypesHitsRepository $linkTypesHitsRepository, Factory $validator)
    {
        $this->linkTypesHitsRepository = $linkTypesHitsRepository;
        $this->validator = $validator;
    }

    public function get(array $input): array
    {
        [
            "startDateTime" => $startDateTime,
            "endDateTime" => $endDateTime
        ] = $this->validate($input);


        $allRecords = $this->linkTypesHitsRepository->getAllRecordsInTimeInterval($startDateTime, $endDateTime);
        $recordsCount = [];

        foreach(LinksTypeEnums::cases() as $linkType){
            $recordsCount[$linkType] = 0;
        }

        foreach($allRecords as $record){
            $recordsCount[$record->linkType]++;
        }

        return $recordsCount;
    }

    private function validate(array $input): array
    {
        return $this->validator->make($input, [
            "startDateTime" => ["required", "string"],
            "endDateTime" => ["required", "string"]
        ],
        [
            "startDateTime.required" => "required",
            "startDateTime.string" => "string",
            "endDateTime.required" => "required",
            "endDateTime.string" => "string"
        ])->validate();
    }
}

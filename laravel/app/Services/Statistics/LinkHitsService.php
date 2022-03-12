<?php

namespace App\Services\Statistics;

use App\Repositories\Statistics\LinkHitsRepository;
use Illuminate\Contracts\Validation\Factory;

class LinkHitsService
{
    private LinkHitsRepository $linkHitsRepository;
    private Factory $validator;

    public function __construct(LinkHitsRepository $linkHitsRepository, Factory $validator)
    {
        $this->linkHitsRepository = $linkHitsRepository;
        $this->validator = $validator;
    }

    public function getStats(array $input): int
    {
        ["link" => $link, "startDateTime" => $startDateTime, "endDateTime" => $endDateTime] = $this->validate($input);

        return $this->linkHitsRepository->get($link, $startDateTime, $endDateTime);
    }

    private function validate(array $input): array
    {
        return $this->validator->make(
            $input,
            [
                "link" => "required",
                "startDateTime" => "required",
                "endDateTime" => "required"
            ],
            [
                "link.required" => "required",
                "startDateTime.required" => "required",
                "endDateTime.required" => "required"
            ])->validate();
    }
}

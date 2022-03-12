<?php


namespace App\Repositories\Statistics;


use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;

class LinkTypesHitsRepository extends BaseRepository
{
    public function getAllRecordsInTimeInterval(string $startDateTime, string $endDateTime): Collection
    {
        return $this->databaseManager->connection()->table("rollup")
            ->where("timestamp", ">=", $startDateTime)
            ->where("timestamp", "<=", $endDateTime)
            ->get("linkType");
    }
}

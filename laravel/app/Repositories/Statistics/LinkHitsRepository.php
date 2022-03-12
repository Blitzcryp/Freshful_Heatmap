<?php


namespace App\Repositories\Statistics;

use App\Repositories\BaseRepository;

class LinkHitsRepository extends BaseRepository
 {
    public function get(string $link, string $startDateTime, string $endDateTime): int
    {
        return $this->databaseManager->connection()->table("rollup")
             ->where("timestamp", ">=", $startDateTime)
             ->where("timestamp", "<=", $endDateTime)
             ->where("link", "=", $link)
             ->count();
    }
}

<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;

class GetUserJourneyRepository extends BaseRepository
{
    public function get(int $id): Collection
    {
        return $this->cache->rememberForever(
            $this->getCacheSignature(__FUNCTION__, [
                "id" => $id
            ]),
            function () use ($id) {
                return $this->databaseManager->connection()->table("rollup")
                    ->where("uid", $id)
                    ->get();
            }
        );
    }

    public function getOtherUsersJourney(int $id, array $uniqueLinks): Collection
    {
        $query = $this->databaseManager->connection()->table("rollup");

        $query = $query->whereIn("link", $uniqueLinks)->whereNot("uid", $id);

        return $this->cache->rememberForever(
            $this->getCacheSignature(__FUNCTION__, [
                "id" => $id,
                "uniqueLinks" => $uniqueLinks
            ]),
            function () use ($query) {
                return $query->get(["uid", "link", "timestamp"]);
            }
        );
    }
}

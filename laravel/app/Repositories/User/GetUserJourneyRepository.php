<?php


namespace App\Repositories\User;


use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;

class GetUserJourneyRepository extends BaseRepository
{
    public function get(int $id): Collection
    {
        return $this->databaseManager->connection()->table("rollup")
            ->where("id", $id)
            ->get();
    }
}

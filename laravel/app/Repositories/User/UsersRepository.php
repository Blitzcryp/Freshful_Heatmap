<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;

class UsersRepository extends BaseRepository
{
    public function userExistsById(int $id): bool
    {
        return $this->cache->rememberForever(
            $this->getCacheSignature(__FUNCTION__, [
                "id" => $id,
            ]),
            function () use ($id) {
                return $this->databaseManager->connection()->table("users")->where("id", $id)->count() > 0;
            }
        );
    }
}

<?php


namespace App\Repositories\User;


use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{
    public function userExistsById(int $id): bool
    {
        return $this->databaseManager->connection()->table("users")->where("id", $id)->count() > 0;
    }
}

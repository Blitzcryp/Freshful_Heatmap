<?php

namespace App\Repositories;

class PostbackRepository extends BaseRepository
{
    public function createRollup($link, $linkType, $timestamp, $uid): bool {
        return $this->databaseManager->connection()->table("rollup")->insert([
            "link" => $link,
            "linkType" => $linkType,
            "timestamp" => $timestamp,
            "uid" => $uid
        ]);
    }
}

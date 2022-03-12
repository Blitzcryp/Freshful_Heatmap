<?php

namespace App\Repositories;

class PostbackRepository extends BaseRepository
{
    public function writePostback($link, $linkType, $timestamp, $uid): bool {
        return $this->databaseManager->connection()->table("postback")->insert([
            "link" => $link,
            "linkType" => $linkType,
            "timestamp" => $timestamp,
            "uid" => $uid
        ]);
    }
}

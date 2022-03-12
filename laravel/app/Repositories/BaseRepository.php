<?php

namespace App\Repositories;

use Illuminate\Database\DatabaseManager;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Support\Collection;

class BaseRepository
{
    protected DatabaseManager $databaseManager;
    protected Cache $cache;

    public function __construct(DatabaseManager $databaseManager, Cache $cache)
    {
        $this->databaseManager = $databaseManager;
        $this->cache = $cache;
    }

    protected function getCacheSignature(string $method, array $params = []): string
    {
        // TODO Make integration test

        $sig = static::class . "::" . $method;

        ksort($params);
        foreach ($params as $k => $v) {
            $sig .= "." . $k . ":" . (is_array($v) || $v instanceof Collection ? json_encode($v) : $v);
        }

        return md5($sig);
    }
}

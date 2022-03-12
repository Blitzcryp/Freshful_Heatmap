<?php

namespace App\Database;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Seeder as LaravelSeeder;

class Seeder extends LaravelSeeder
{
    protected DatabaseManager $databaseManager;
    protected Hasher $hasher;


    public function __construct(DatabaseManager $databaseManager, Hasher $hasher)
    {
        $this->databaseManager = $databaseManager;
        $this->hasher = $hasher;
    }
}

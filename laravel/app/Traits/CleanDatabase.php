<?php

namespace App\Traits;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\DatabaseManager;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

trait CleanDatabase
{
    protected array $excludeTablesFromCleaning = ["migrations"];

    public function cleanDatabase(string $databaseName)
    {
        $tables = $this->getTableNames($databaseName);

        Schema::disableForeignKeyConstraints();
        foreach ($tables as $table) {
            $this->truncateTable($table["TABLE_NAME"]);
        }
        Schema::enableForeignKeyConstraints();
    }

    private function refreshTestDatabase()
    {
        if (!RefreshDatabaseState::$migrated) {
            $this->artisan("migrate:fresh");

            $this->app[Kernel::class]->setArtisan(null);

            RefreshDatabaseState::$migrated = true;
        }
    }

    private function getTableNames(string $databaseName): Collection
    {
        /** @var DatabaseManager $dbm */
        $dbm = $this->getDatabaseManager();

        return $dbm
            ->connection()
            ->query()
            ->from("information_schema.tables")
            ->where("TABLE_SCHEMA", $databaseName)
            ->whereNotIn("TABLE_NAME", $this->excludeTablesFromCleaning)
            ->get()
            ->map(fn($item) => (array) $item);
    }

    private function truncateTable(string $tableName)
    {
        /** @var DatabaseManager $dbm */
        $dbm = $this->getDatabaseManager();

        $dbm->table($tableName)->truncate();
    }
}

<?php


namespace Tests;


use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\DatabaseManager;
use App\Traits\CleanDatabase;

class DbTestCase extends TestCase
{
    use CleanDatabase;

    protected function getDatabaseManager(): DatabaseManager
    {
        return $this->app->get(DatabaseManager::class);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->refreshTestDatabase();

        $this->cleanDatabase("__testing__");
    }

    protected function createUser(array $data = []): array
    {
        /** @var Hasher $hasher */
        $hasher = $this->app->get(Hasher::class);

        $defaults = [
            "fname" => "Coman",
            "lname" => "Cosmin-Alexandru",
            "last_login_country" => "RO",
            "email" => "comancosmin112@gmail.com",
            "email_verified_at" => null,
            "timezone" => "Europe/Bucharest",
            "password_hash" => $hasher->make("parola123"),
        ];

        $finalData = array_merge($defaults, $data);

        $userId = $this->getDatabaseManager()
            ->table("users")
            ->insertGetId($finalData);

        $finalData["id"] = $userId;

        return $finalData;
    }
}

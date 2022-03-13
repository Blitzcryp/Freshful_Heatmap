<?php

namespace Database\Seeders;

use App\Database\Seeder;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                "id" => 1,
                "fname" => "Coman",
                "lname" => "Cosmin-Alexandru",
                "last_login_country" => "RO",
                "email" => "comancosmin112@gmail.com",
                "email_verified_at" => null,
                "timezone" => "Europe/Bucharest",
                "password_hash" => $this->hasher->make("parola123"),
            ],
            [
                "id" => 2,
                "fname" => "Coman",
                "lname" => "NotCosmin-NotAlexandru",
                "last_login_country" => "GB",
                "email" => "comancosmin911@gmail.com",
                "email_verified_at" => Carbon::yesterday()->toDateTime(),
                "timezone" => "Europe/Athens",
                "password_hash" => $this->hasher->make("321alorap"),
            ],
            [
                "id" => 3,
                "fname" => "Coman Test1",
                "lname" => "Cadius",
                "last_login_country" => "GB",
                "email" => "comancosmin_crusade@gmail.com",
                "email_verified_at" => Carbon::yesterday()->toDateTime(),
                "timezone" => "Europe/Athens",
                "password_hash" => $this->hasher->make("321alorap"),
            ],
            [
                "id" => 4,
                "fname" => "Coman Test2",
                "lname" => "Ezekiel",
                "last_login_country" => "FR",
                "email" => "comancosmin_warhammer@gmail.com",
                "email_verified_at" => Carbon::yesterday()->toDateTime(),
                "timezone" => "Europe/Athens",
                "password_hash" => $this->hasher->make("321alorap"),
            ],
            [
                "id" => 5,
                "fname" => "Coman Test3",
                "lname" => "Diablo",
                "last_login_country" => "FR",
                "email" => "comancosmin_warhammer@hell.com",
                "email_verified_at" => Carbon::yesterday()->toDateTime(),
                "timezone" => "Europe/Athens",
                "password_hash" => $this->hasher->make("321alorap"),
            ],

        ];

        $this->databaseManager
            ->connection()
            ->table("users")
            ->insert($users);
    }
}

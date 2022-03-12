<?php

use App\Enums\LinksTypeEnums;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rollup', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("uid");
            $table->string("link");
            $table->enum("linkType", LinksTypeEnums::cases());
            $table->dateTime("timestamp");

            $table->foreign("uid")->references("id")->on("users");
        });
    }

    public function down()
    {
        Schema::dropIfExists('rollup');
    }
};

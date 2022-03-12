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
            $table->integer("uid")->unsigned();
            $table->string("link");
            $table->enum("linkType", LinksTypeEnums::cases());
            $table->dateTime("timestamp");

            /*$table->foreign("uid")
                ->references("id")
                ->on("users")
                ->onDelete("restrict")
                ->onUpdate("cascade");
            */
        });
    }

    public function down()
    {
        Schema::dropIfExists('rollup');
    }
};

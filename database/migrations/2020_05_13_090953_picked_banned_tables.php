<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PickedBannedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_banned_maps', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('match_id');
            $table->foreign('match_id', 'mtid_matches_fk')->references('id')->on('matches')->onDelete('cascade');
            $table->unsignedInteger('map_id');
            $table->foreign('map_id', 'mpid_matches_fk')->references('id')->on('matches')->onDelete('cascade');
            $table->unsignedInteger('banned_by')->nullable();
            $table->foreign('banned_by')->references('id')->on('players')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('match_picked_maps', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('match_id');
            $table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade');
            $table->unsignedInteger('map_id');
            $table->foreign('map_id')->references('id')->on('matches')->onDelete('cascade');
            $table->unsignedInteger('picked_by')->nullable();
            $table->foreign('picked_by')->references('id')->on('players')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_banned_maps');
        Schema::dropIfExists('match_picked_maps');
    }
}

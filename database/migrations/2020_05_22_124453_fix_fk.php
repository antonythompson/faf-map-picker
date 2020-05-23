<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('match_picked_maps', function (Blueprint $table) {
            $table->dropForeign('match_picked_maps_map_id_foreign');
        });
        Schema::table('match_banned_maps', function (Blueprint $table) {
            $table->dropForeign('mpid_matches_fk');
        });
        Schema::table('match_banned_maps', function (Blueprint $table) {
            $table->foreign('map_id', 'match_ban_map_id')->references('id')->on('maps')->onDelete('cascade');
        });
        Schema::table('match_picked_maps', function (Blueprint $table) {
            $table->foreign('map_id', 'match_pick_map_id')->references('id')->on('maps')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}

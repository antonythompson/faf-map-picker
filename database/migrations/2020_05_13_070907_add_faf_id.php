<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFafId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('maps', function (Blueprint $table) {
            $table->integer('faf_id')->after('id');
            $table->string('thumbnail_url')->nullable()->after('name');
            $table->integer('max_players')->nullable()->after('name');
            $table->integer('version')->nullable()->after('name');
            $table->integer('height')->nullable()->after('name');
            $table->integer('width')->nullable()->after('name');
            $table->text('description')->nullable()->after('name');
            $table->string('download_url')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maps', function (Blueprint $table) {
            $table->dropColumn('faf_id');
            $table->dropColumn('thumbnail_url');
            $table->dropColumn('max_players');
            $table->dropColumn('version');
            $table->dropColumn('height');
            $table->dropColumn('width');
            $table->dropColumn('description');
            $table->dropColumn('download_url');
        });
    }
}

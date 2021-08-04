<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMemberInstructorColumnBoatTripTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('boat_trip', function (Blueprint $table) {
            $table->boolean('is_instructor')->default(false);
            $table->boolean('is_member')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('boat_trip', function (Blueprint $table) {
            $table->dropColumn('is_instructor');
            $table->dropColumn('is_member');
        });
    }
}

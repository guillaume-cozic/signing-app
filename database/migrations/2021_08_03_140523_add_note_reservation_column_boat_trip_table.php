<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoteReservationColumnBoatTripTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('boat_trip', function (Blueprint $table) {
            $table->text('note')->nullable();
            $table->boolean('is_reservation')->default(false)->nullable();
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
            $table->dropColumn('note');
            $table->dropColumn('is_reservation');
        });
    }
}

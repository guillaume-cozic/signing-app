<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoatTripTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boat_trip', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->dateTime('start_at', 6)->nullable();
            $table->dateTime('end_at', 6)->nullable();
            $table->float('number_hours')->nullable();
            $table->string('name')->nullable();
            $table->integer('member_id')->nullable();
            $table->json('boats')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boat_trip');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalPackageTable extends Migration
{
    public function up()
    {
        Schema::create('rental_package', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name')->nullable();
            $table->string('validity')->nullable();
            $table->json('fleets')->nullable();
            $table->integer('sailing_club_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rental_package');
    }
}

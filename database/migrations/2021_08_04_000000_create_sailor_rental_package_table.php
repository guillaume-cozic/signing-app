<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSailorRentalPackageTable extends Migration
{
    public function up()
    {
        Schema::create('sailor_rental_package', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->float('hours')->nullable();
            $table->date('end_validity')->nullable();
            $table->integer('rental_package_id')->nullable();
            $table->integer('sailing_club_id');
            $table->integer('sailor_id');
            $table->timestamps();
        });

        Schema::create('sailor', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sailor_rental_package');
        Schema::dropIfExists('sailor');
    }
}

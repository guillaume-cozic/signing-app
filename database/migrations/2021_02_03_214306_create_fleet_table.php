<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFleetTable extends Migration
{
    public function up()
    {
        Schema::create('fleet', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->integer('total_available');
            $table->json('name')->default(null)->nullable();
            $table->enum('state', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fleet');
    }
}

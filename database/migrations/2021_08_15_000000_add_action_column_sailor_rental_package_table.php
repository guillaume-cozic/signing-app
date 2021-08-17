<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActionColumnSailorRentalPackageTable extends Migration
{
    public function up()
    {
        Schema::table('sailor_rental_package', function (Blueprint $table) {
            $table->json('actions')->nullable()->default(null);
        });
    }

    public function down()
    {
        Schema::table('sailor_rental_package', function (Blueprint $table) {
            $table->dropColumn('actions');
        });
    }
}

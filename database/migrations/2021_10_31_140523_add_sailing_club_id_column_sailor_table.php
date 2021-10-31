<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSailingClubIdColumnSailorTable extends Migration
{
    public function up()
    {
        Schema::table('sailor', function (Blueprint $table) {
            $table->integer('sailing_club_id');
        });
    }

    public function down()
    {
        Schema::table('sailor', function (Blueprint $table) {
            $table->dropColumn('sailing_club_id');
        });
    }
}

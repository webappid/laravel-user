<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddUriMethodInRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->string('uri', 100)
                ->after('name')
                ->nullable(false)
                ->default('/');

            $table->string('method', 10)
                ->after('uri')
                ->nullable(false)
                ->default('GET');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_routes', function (Blueprint $table) {
            //
        });
    }
}

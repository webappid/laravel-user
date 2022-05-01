<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_routes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('route_id')
                ->nullable(false)
                ->comment('relation to app routes table');
            $table->unsignedInteger('role_id')
                ->nullable(false)
                ->comment('relation to roles table');
            $table->timestamps();

            /**
             * relation
             */

            $table->foreign('route_id')->references('id')->on('routes')->cascadeOnDelete();
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_role_routes');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('role_id')->unsigned()->nullable(true);
            $table->integer('permission_id')->unsigned()->nullable(true);
            $table->unsignedBigInteger('created_by')->nullable(true);
            $table->unsignedBigInteger('updated_by')->nullable(true);
            $table->timestamps();

            /**
             * Relation
             */
            $table->foreign('role_id')
                ->references('id')
                ->on('roles');
            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions');
            $table->foreign('created_by')
                ->references('id')
                ->on('users');
            $table->foreign('updated_by')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_permissions');
    }
}

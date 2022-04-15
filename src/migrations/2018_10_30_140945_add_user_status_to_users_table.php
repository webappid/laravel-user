<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class AddUserStatusToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('status_id')
                ->after('email')
                ->unsigned()
                ->nullable(true)
                ->comment('Users status');

            /**
             * Relation to database
             */
            $table->foreign('status_id')
                ->references('id')
                ->on('user_statuses')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        if (Config::get('database.default') != 'sqlite') {
            $columns = [];
            $foreigns = [];
            if (Schema::hasColumn('users', 'status_id')) {
                array_push($columns, 'status_id');
                array_push($foreigns, 'status_id');
            }


            if (count($columns)) {
                Schema::table('users', function (Blueprint $table) use ($foreigns) {
                    $table->dropForeign($foreigns);
                });

                Schema::table('users', function (Blueprint $table) use ($columns) {
                    $table->dropColumn($columns);
                });
            }
        }
    }
}

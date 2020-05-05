<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterApiTokenUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('api_token', 80)->after('password')
                ->unique()
                ->nullable()
                ->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $driver = Schema::connection($this->getConnection())->getConnection()->getDriverName();

        if ($driver != 'sqlite') {
            $columns = [];
            if (Schema::hasColumn('users', 'api_token')) {
                array_push($columns, 'api_token');
            }

            if (count($columns) > 0) {
                Schema::table('users', function (Blueprint $table) use ($columns) {
                    $table->dropColumn($columns);
                });
            }
        }
    }
}

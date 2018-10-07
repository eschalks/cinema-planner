<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SimplifyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users',
            function (Blueprint $table) {
                $table->dropColumn([
                    'email',
                    'email_verified_at',
                    'password',
                    'remember_token',
                ]);

                $table->unique(['name']);
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users',
            function (Blueprint $table) {
                $table->dropUnique(['name']);

                $table->string('email')->after('name')->unique();
                $table->timestamp('email_verified_at')
                      ->after('email')
                      ->nullable();
                $table->string('password')->after('email_verified_at');
                $table->rememberToken()->after('password');
            });
    }
}

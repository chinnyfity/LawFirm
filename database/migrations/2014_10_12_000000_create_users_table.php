<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 50)->nullable();
            $table->string('firstname', 50)->nullable();
            $table->string('lastname', 50)->nullable();
            $table->string('username', 30)->nullable();
            $table->string('email', 50)->unique();
            $table->string('phone', 30)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('verification_code', 50)->nullable();
            $table->string('password', 100);
            $table->string('pics', 50)->nullable();
            $table->string('interests', 50)->nullable();
            $table->rememberToken();
            $table->timestamps()->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->index(['uuid', 'email', 'phone']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

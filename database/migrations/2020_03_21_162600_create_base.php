<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        #Table to store Users, can be synced from LDAP Server
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->char('ldap_uuid', 50)->nullable(true);
            $table->char('username', 50)->unique();
            $table->char('first_name', 50)->nullable(true);
            $table->char('last_name', 50)->nullable(true);
            $table->char('email', 100)->nullable(true);
            $table->boolean('is_admin');
            $table->char('password_hash', 100)->nullable(true);
            $table->boolean('enabled');
            $table->timestamps();
        });

        #Table to store User Tokens (Access Cards)
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->char('name', 50)->nullable(true);
            $table->char('token_hash', 100);
            $table->boolean('enabled');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });

        #Table to store Gate Managers (Devices that talk to this API)
        Schema::create('gate_managers', function (Blueprint $table) {
            $table->id();
            $table->char('name', 50);
            $table->string('notes')->nullable(true);
            $table->char('api_key', 50)->unique();
            $table->macAddress('mac')->nullable(true);
            $table->boolean('enabled');
            $table->timestamps();
        });
        #Table to store the Physical Devices
        Schema::create('gates', function (Blueprint $table) {
            $table->id();
            $table->char('name', 50);
            $table->char('nice_name', 50);
            $table->string('notes')->nullable(true);
            $table->foreignId('gate_manager_id');
            $table->boolean('enabled');
            $table->timestamps();

            $table->foreign('gate_manager_id')->references('id')->on('gate_managers')->onDelete('restrict');
        });

        #Table to store User Access Rights
        Schema::create('user_access_rights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('gate_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('gate_id')->references('id')->on('gates')->onDelete('cascade');


        });

        #Table to store events
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gate')->nullable(true);
            $table->foreignId('user')->nullable(true);
            $table->string('event');
            $table->char('newState', 50)->nullable(true);
            $table->smallInteger('category');
            $table->timestamps();

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
        Schema::dropIfExists('user_tokens');
        Schema::dropIfExists('gate_managers');
        Schema::dropIfExists('gates');
        Schema::dropIfExists('user_access_rights');
        Schema::dropIfExists('events');
    }
}

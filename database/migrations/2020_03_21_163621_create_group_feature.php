<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupFeature extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Table to store user Groups
        Schema::create('user_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->boolean('admin');
            $table->timestamps();
        });
        //Table to store Group Membership
        Schema::create('user_user_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('user_group_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_group_id')->references('id')->on('user_groups')->onDelete('cascade');
        });
        //Table to store Group Access Rights
        Schema::create('user_group_access_rights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_group_id');
            $table->foreignId('gate_id');
            $table->timestamps();

            $table->foreign('user_group_id')->references('id')->on('user_groups')->onDelete('cascade');
            $table->foreign('gate_id')->references('id')->on('gates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_groups');
        Schema::dropIfExists('user_user_group');
        Schema::dropIfExists('user_group_access_rights');
    }
}

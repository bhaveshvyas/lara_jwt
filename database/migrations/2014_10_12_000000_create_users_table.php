<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('ids')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('module')->nullable();
            $table->string('social_network')->nullable();
            $table->string('category')->nullable();
            $table->integer('team_id')->nullable();
            $table->integer('login_type')->nullable();
            $table->integer('can_post')->nullable();
            $table->string('pid')->nullable();
            $table->string('token')->nullable();
            $table->string('avatar')->nullable();
            $table->string('url')->nullable();
            $table->text('tmp')->nullable();
            $table->longText('data')->nullable();
            $table->longText('proxy')->nullable();
            $table->integer('status')->nullable();
            $table->integer('changed')->nullable();
            $table->integer('created')->nullable();
            $table->integer('user_type')->nullable();
            $table->integer('verified_creator')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

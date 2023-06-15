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
            $table->string('identity_card_user')->unique();
            $table->string('names');
            $table->string('surnames');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('address');
            $table->string('password');
            $table->string('profile_picture')->nullable();
            $table->text('profesional_description')->nullable();
            $table->integer('user_state');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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

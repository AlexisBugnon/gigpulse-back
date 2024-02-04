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
            $table->string('name')->nullable(false);
            $table->string('email')->nullable(false)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            // Default profile photo if the user does not provide one
            $table->string('profile_picture')->nullable()->default('https://api.dicebear.com/7.x/pixel-art/svg');
            // By default will change to user
            $table->enum('role', ['Super admin', 'Admin', 'User'])->default('User');
            // By default will be active
            $table->string('description')->nullable(true);
            $table->string('job')->nullable(true);
            $table->boolean('is_active')->default(true);
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

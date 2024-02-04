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
        Schema::create('gigs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            // $table->bigInteger('user_id')->unsigned();
            $table->unsignedBigInteger('category_id');
            // $table->bigInteger('category_id')->unsigned();
            // Integration of the foreign keys -> connection with other tables
            // Method used to specify that the foreign key is constrained
            // If a row in the users table is deleted, all corresponding rows in the current table (the one where the code is located) will also be deleted in cascade
            $table->foreign('user_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->string('title')->nullable(false);
            $table->string('picture')->nullable(false);
            $table->text('description')->nullable(false);
            $table->decimal('price', 10, 2)->nullable(false);
            $table->decimal('average_rating', 10, 1)->nullable(true);
            $table->boolean('is_active')->default(true);
            $table->string('slug')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gigs');
    }
};

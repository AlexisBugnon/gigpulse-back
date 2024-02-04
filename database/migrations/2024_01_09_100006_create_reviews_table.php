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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id(); // clé primaire de la table
            $table->bigInteger('user_id')->unsigned(); // bidInt stocke des nombres entiers très grands 64bits en sql et unsigned = unsigned
            $table->bigInteger('gig_id')->unsigned();
            // Integration of the foreign keys -> connection with other tables with additional constraints on the foreign key constrained()
            // onDelete('cascade') = if a user is deleted from the users table, all reviews associated with that user in the reviews table will also be deleted
            $table->foreign('user_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->foreign('gig_id')->references('id')->on('gigs')->constrained()->onDelete('cascade');
            $table->integer('rating')->nullable(false);
            $table->text('comment')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};

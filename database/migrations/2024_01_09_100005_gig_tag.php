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
        Schema::create('gig_tag', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tag_id')->unsigned();
            $table->bigInteger('gig_id')->unsigned();
            // Integration of the foreign keys -> connection with other tables
            $table->foreign('tag_id')->references('id')->on('tags')->constrained()->onDelete('cascade');
            $table->foreign('gig_id')->references('id')->on('gigs')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gig_tag');
    }
};

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
        Schema::create('characters', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->string('name');
            $table->string('status');
            $table->string('species');
            $table->string('type');
            $table->string('gender');
            $table->string('origin_name');
            $table->string('origin_url');
            $table->string('location_name');
            $table->string('location_url');
            $table->string('image');
            $table->string('url');
            $table->string('created');
            $table->json('episode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};

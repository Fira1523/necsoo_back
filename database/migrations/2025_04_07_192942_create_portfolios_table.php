<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('category', ['ongoing', 'completed', 'upcoming']);
            $table->string('image');
            $table->text('description');
            $table->string('location');
            $table->string('duration');
            $table->string('budget');
            $table->text('partners');
            $table->unsignedTinyInteger('progress');
            $table->text('impact');
            $table->text('objectives');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};

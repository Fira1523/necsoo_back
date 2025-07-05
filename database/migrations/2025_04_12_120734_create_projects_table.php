<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('category', ['ongoing', 'completed', 'upcoming']);
            $table->string('image')->nullable();
            $table->string('location');
            $table->string('duration');
            $table->string('budget')->nullable();
            $table->text('partners')->nullable();
            $table->integer('progress')->default(0);
            $table->text('impact')->nullable();
            $table->text('objectives')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExperienceProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experience_project', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('experience_id') -> unsigned();
            $table->bigInteger('project_id') -> unsigned();
            $table->foreign('experience_id') -> references('id') -> on('experiences') -> onDelete('cascade');
            $table->foreign('project_id') -> references('id') -> on('projects') -> onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('experience_project');
    }
}

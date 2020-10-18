<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->text('task_name');
            $table->text('task_description');
            $table->unsignedBigInteger('creator_id');
            $table->unsignedBigInteger('assignee_id');
            $table->string('deadline');
            $table->double('urgency')->default(1);
            $table->double('status')->default(1);
            $table->boolean("is_private")->default(false);
            $table->string('estimated_time')->nullable();
            $table->string('done_time')->nullable();
            $table->timestamps();

            $table->foreign('creator_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('assignee_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}

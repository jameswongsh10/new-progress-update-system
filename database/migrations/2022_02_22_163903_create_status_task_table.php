<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_task', function (Blueprint $table) {
            $table->id();
            $table->foreignId('status_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('task_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('task_description');
            $table->string('task_remark');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_tasks');
    }
}

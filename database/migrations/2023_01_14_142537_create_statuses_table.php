<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('statuses');

        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('action')->default('Status Change.');
            $table->text('detail');
            $table->foreignId('task_id');
            $table->timestamps();

            $table->foreign('task_id')->references('id')->on('tasks')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statuses');
    }
};

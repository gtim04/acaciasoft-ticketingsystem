<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100);
            $table->string('importance', 25);
            $table->unsignedBigInteger('user_id');
            $table->integer('ticket_handler')->nullable();
            $table->text('title');
            $table->text('description');
            $table->dateTime('issue_date', 0);
            $table->string('status', 20)->default('open');
            $table->boolean('isCompleted')->default(0);
            $table->boolean('isDeleted')->default(0);
            $table->timestamps();
            
            //foreign key
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('tickets');
    }
}

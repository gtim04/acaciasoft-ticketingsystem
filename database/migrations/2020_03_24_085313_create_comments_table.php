<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->string('content');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            //foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        //linking table
        Schema::create('comment_ticket', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comment_id');
            $table->unsignedBigInteger('ticket_id');
            $table->timestamps();
            //make unique so you dont associate a comment to many tickets
            $table->unique(['comment_id','ticket_id']);
            //foreign keys
            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}

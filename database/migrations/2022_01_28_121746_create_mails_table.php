<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mails', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Імя клієнта');
            $table->string('email')->comment('Email клієнта');
            $table->text('text')->comment('Текст звернення');
            $table->enum('status', ['reviewed', 'not_reviewed'])->comment('Cтатус розгляду звернення')
                ->default('not_reviewed');
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
        Schema::dropIfExists('mails');
    }
}

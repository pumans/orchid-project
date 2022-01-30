<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20)->nullable()->comment('Номер телефона');
            $table->enum('status', ['interviewed', 'not_interviewed'])->comment('Cтатус')->default('not_interviewed');
            $table->string('name')->nullable()->comment('Имя');
            $table->string('last_name')->nullable()->comment('Фамилия');
            $table->string('email')->nullable();
            $table->bigInteger('mail_id')->comment('Обращение с сайта')->unsigned()->nullable();
            $table->bigInteger('phone_id')->comment('Обращение с сайта')->unsigned()->nullable();
            $table->date('birthday')->nullable()->comment('Дата народження');
            $table->foreign('mail_id')
                ->references('id')
                ->on('mails')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->foreign('phone_id')
                ->references('id')
                ->on('phones')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->string('assessment')->comment('Враження від клієнта')->nullable();
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
        Schema::dropIfExists('clients');
    }
}

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
        Schema::create('certificados', function (Blueprint $table) {
            $table->id();
            $table->string('consecutive')->unique();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('cliente_id')->unsigned();
            $table->bigInteger('instructor_id')->unsigned();
            $table->bigInteger('aliado_id')->unsigned()->nullable();
            $table->bigInteger('representante_legal_id')->unsigned();
            $table->bigInteger('curso_id')->unsigned();
            $table->bigInteger('horario_id')->unsigned();
            $table->string('course_name');
            $table->string('hours');
            $table->date('initial_date');
            $table->date('final_date');
            $table->date('expiration_date');
            $table->boolean('active')->default(1);
            $table->boolean('status')->default(1);
            $table->boolean('deleted')->default(0);
            $table->timestamps();
        });

        Schema::table('certificados', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('instructor_id')->references('id')->on('instructores')->onDelete('cascade');
            $table->foreign('aliado_id')->references('id')->on('aliados')->onDelete('cascade');
            $table->foreign('representante_legal_id')->references('id')->on('representante_legals')->onDelete('cascade');
            $table->foreign('curso_id')->references('id')->on('cursos')->onDelete('cascade');
            $table->foreign('horario_id')->references('id')->on('horarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certificados');
    }
};

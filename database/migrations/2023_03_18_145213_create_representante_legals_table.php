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
        Schema::create('representante_legals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name');
            $table->bigInteger('tipo_documento_id')->unsigned();
            $table->bigInteger('document');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('signature');
            $table->boolean('default')->default(0);
            $table->boolean('status')->default(1);
            $table->boolean('deleted')->default(0);
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
        Schema::dropIfExists('representante_legals');
    }
};

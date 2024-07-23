<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubButirInstrumensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_butir_instrumens', function (Blueprint $table) {
            $table->id();
            $table->text('nama_sub_butir')->nullable();
            $table->enum('upload_file', ['0', '1']);
            $table->unsignedBigInteger('butir_instrumen_id')->nullable();
            $table->unsignedBigInteger('input_by')->nullable();
            $table->timestamps();

            $table->foreign('butir_instrumen_id')->references('id')->on('butir_instrumens')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('input_by')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_butir_instrumens');
    }
}

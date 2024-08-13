<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileSubButirInstrumensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_sub_butir_instrumens', function (Blueprint $table) {
            $table->id();
            $table->string('file_upload')->nullable();
            $table->unsignedBigInteger('sub_butir_instrumen_id')->nullable();
            $table->enum('tampilkan', ['0', '1']);
            $table->unsignedBigInteger('upload_by')->nullable();
            $table->timestamps();

            $table->foreign('sub_butir_instrumen_id')->references('id')->on('sub_butir_instrumens')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('upload_by')->references('id')->on('users')
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
        Schema::dropIfExists('file_sub_butir_instrumens');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordTemuansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('record_temuans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jadwal_ami_id')->nullable();
            $table->string('no_hp')->nullable();
            $table->text('isi_keterangan')->nullable();
            $table->date('tanggal_input')->nullable();
            $table->timestamps();

            $table->foreign('jadwal_ami_id')->references('id')->on('jadwal_amis')
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
        Schema::dropIfExists('record_temuans');
    }
}

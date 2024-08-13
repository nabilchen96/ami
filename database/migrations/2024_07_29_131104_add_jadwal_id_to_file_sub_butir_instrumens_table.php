<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJadwalIdToFileSubButirInstrumensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_sub_butir_instrumens', function (Blueprint $table) {
            $table->unsignedBigInteger('jadwal_ami_id')->after('file_upload')->nullable();

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
        Schema::table('file_sub_butir_instrumens', function (Blueprint $table) {
            //
        });
    }
}

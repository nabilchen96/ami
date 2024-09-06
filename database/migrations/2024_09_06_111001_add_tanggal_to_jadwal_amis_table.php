<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTanggalToJadwalAmisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jadwal_amis', function (Blueprint $table) {
            $table->date('tgl_awal_penilaian')->after('tgl_akhir_upload')->nullable();
            $table->date('tgl_akhir_penilaian')->after('tgl_awal_penilaian')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jadwal_amis', function (Blueprint $table) {
            //
        });
    }
}

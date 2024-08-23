<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTtdToBeritaAcarasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('berita_acaras', function (Blueprint $table) {
            $table->string('ttd_auditor')->after('is_publish')->nullable();
            $table->string('ttd_auditee')->after('ttd_auditor')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('berita_acaras', function (Blueprint $table) {
            //
        });
    }
}

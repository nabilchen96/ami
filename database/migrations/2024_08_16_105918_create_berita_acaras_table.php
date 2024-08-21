<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

class CreateBeritaAcarasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('berita_acaras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jadwal_ami_id')->nullable();
            $table->unsignedBigInteger('lead_auditor')->nullable();
            $table->string('nomor_surat')->nullable();
            $table->string('nama_auditee')->nullable();
            $table->string('nip_auditee')->nullable();
            $table->text('isi_ba')->nullable();
            $table->enum('is_publish',['0','1']);
            $table->unsignedBigInteger('input_id')->nullable();
            $table->timestamps();

            $table->foreign('jadwal_ami_id')->references('id')->on('jadwal_amis')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('lead_auditor')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->foreign('input_id')->references('id')->on('users')
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
        Schema::dropIfExists('berita_acaras');
    }
}

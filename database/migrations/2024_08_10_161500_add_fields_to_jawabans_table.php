<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToJawabansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jawabans', function (Blueprint $table) {
            $table->unsignedBigInteger('sub_butir_instrumen_id')->after('butir_instrumen_id')->nullable();
            $table->float('skor_persen1')->after('sub_grup_id')->nullable();
            $table->float('skor_persen2')->after('skor_persen1')->nullable();
            $table->float('skor_persen3')->after('skor_persen2')->nullable();
            $table->foreign('sub_butir_instrumen_id')->references('id')->on('sub_butir_instrumens')
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
        Schema::table('jawabans', function (Blueprint $table) {
            //
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNilaiToSubButirInstrumensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_butir_instrumens', function (Blueprint $table) {
            $table->double('skor_persen_1')->after('butir_instrumen_id')->nullable();
            $table->double('skor_persen_2')->after('skor_persen_1')->nullable();
            $table->double('skor_persen_3')->after('skor_persen_2')->nullable();
            $table->double('skor_rata')->after('skor_persen_3')->nullable();
            $table->double('skor_convert')->after('skor_rata')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_butir_instrumens', function (Blueprint $table) {
            //
        });
    }
}

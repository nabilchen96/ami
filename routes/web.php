<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//LOGIN
Route::get('/login', 'App\Http\Controllers\AuthController@login')->name('login');
Route::get('/', 'App\Http\Controllers\AuthController@login');
Route::post('/loginProses', 'App\Http\Controllers\AuthController@loginProses');

// Route::get('/', function(){
//     return view('frontend.landing');
// });

//BACKEND
Route::group(['middleware' => 'auth'], function () {

    //DASHBOARD
    Route::get('/dashboard', 'App\Http\Controllers\DashboardController@index');

    //USER
    Route::get('/user', 'App\Http\Controllers\UserController@index');
    Route::get('/data-user', 'App\Http\Controllers\UserController@data');
    Route::post('/store-user', 'App\Http\Controllers\UserController@store');
    Route::post('/update-user', 'App\Http\Controllers\UserController@update');
    Route::post('/delete-user', 'App\Http\Controllers\UserController@delete');

    // GRUP INSTRUMEN
    Route::get('/grup_instrumen', 'App\Http\Controllers\GrupInstrumenController@index');
    Route::get('/data-grup_instrumen', 'App\Http\Controllers\GrupInstrumenController@data');
    Route::post('/store-grup_instrumen', 'App\Http\Controllers\GrupInstrumenController@store');
    Route::post('/update-grup_instrumen', 'App\Http\Controllers\GrupInstrumenController@update');
    Route::post('/delete-grup_instrumen', 'App\Http\Controllers\GrupInstrumenController@delete');

    // SUB GRUP INSTRUMEN
    Route::get('/sub_grup', 'App\Http\Controllers\SubGrupController@index');
    Route::get('/data-sub_grup', 'App\Http\Controllers\SubGrupController@data');
    Route::post('/store-sub_grup', 'App\Http\Controllers\SubGrupController@store');
    Route::post('/update-sub_grup', 'App\Http\Controllers\SubGrupController@update');
    Route::post('/delete-sub_grup', 'App\Http\Controllers\SubGrupController@delete');

    // KURIKULUM INSTRUMEN
    Route::get('/kurikulum_instrumen', 'App\Http\Controllers\KurikulumInstrumenController@index');
    Route::get('/data-kurikulum_instrumen', 'App\Http\Controllers\KurikulumInstrumenController@data');
    Route::post('/store-kurikulum_instrumen', 'App\Http\Controllers\KurikulumInstrumenController@store');
    Route::post('/update-kurikulum_instrumen', 'App\Http\Controllers\KurikulumInstrumenController@update');
    Route::post('/delete-kurikulum_instrumen', 'App\Http\Controllers\KurikulumInstrumenController@delete');

    // BUTIR INSTRUMEN
    Route::get('/butir_instrumen/{kurikulum_id}', 'App\Http\Controllers\ButirInstrumenController@index');
    Route::get('/data-butir_instrumen/{kurikulum_id}', 'App\Http\Controllers\ButirInstrumenController@data');
    Route::get('butir_instrumen/edit-butir_instrumen/{id}', 'App\Http\Controllers\ButirInstrumenController@edit');
    Route::post('/store-butir_instrumen', 'App\Http\Controllers\ButirInstrumenController@store');
    Route::post('/update-butir_instrumen', 'App\Http\Controllers\ButirInstrumenController@update');
    Route::post('/delete-butir_instrumen', 'App\Http\Controllers\ButirInstrumenController@delete');

    // SUB BUTIR INSTRUMEN
    Route::get('/subbutir_instrumen/{butir_instrumen_id}', 'App\Http\Controllers\SubButirInstrumenController@index');
    Route::get('/data-subbutir_instrumen/{butir_instrumen_id}', 'App\Http\Controllers\SubButirInstrumenController@data');
    Route::get('subbutir_instrumen/edit-butir_instrumen/{id}', 'App\Http\Controllers\SubButirInstrumenController@edit');
    Route::post('/store-subbutir_instrumen', 'App\Http\Controllers\SubButirInstrumenController@store');
    Route::post('/store-subbutir_instrumen_bulk', 'App\Http\Controllers\SubButirInstrumenController@store_bulk');
    Route::post('/update-subbutir_instrumen', 'App\Http\Controllers\SubButirInstrumenController@update');
    Route::post('/delete-subbutir_instrumen', 'App\Http\Controllers\SubButirInstrumenController@delete');

    // FILE SUB BUTIR
    Route::get('/file_subbutir_instrumen/{sub_butir_instrumen_id}/{jadwal_ami_id}', 'App\Http\Controllers\FileSubButirInstrumenController@index');
    Route::get('/data-file_subbutir_instrumen/{sub_butir_instrumen_id}/{jadwal_ami_id}', 'App\Http\Controllers\FileSubButirInstrumenController@data');
    Route::post('/store-file_subbutir_instrumen', 'App\Http\Controllers\FileSubButirInstrumenController@store');
    Route::post('/update-file_subbutir_instrumen', 'App\Http\Controllers\FileSubButirInstrumenController@update');
    Route::post('/delete-file_subbutir_instrumen', 'App\Http\Controllers\FileSubButirInstrumenController@delete');

    Route::get('/sub_grups_by_grup_id/{id}', 'App\Http\Controllers\ButirInstrumenController@getSubGrup');

    // JADWAL AMI
    Route::get('/jadwal_ami', 'App\Http\Controllers\JadwalAmiController@index');
    Route::get('/data-jadwal_ami', 'App\Http\Controllers\JadwalAmiController@data');
    Route::get('jadwal_ami/edit-jadwal_ami/{id}', 'App\Http\Controllers\JadwalAmiController@edit');
    Route::post('/store-jadwal_ami', 'App\Http\Controllers\JadwalAmiController@store');
    Route::post('/update-jadwal_ami', 'App\Http\Controllers\JadwalAmiController@update');
    Route::post('/delete-jadwal_ami', 'App\Http\Controllers\JadwalAmiController@delete');

    // PENILAIAN AMI
    Route::get('/penilaian_ami', 'App\Http\Controllers\PenilaianController@index');
    Route::get('/penilaian_ami/{id}', 'App\Http\Controllers\PenilaianController@detail');
    Route::get('/data-penilaian_ami/{id}', 'App\Http\Controllers\PenilaianController@data');
    Route::post('/store-penilaian_ami', 'App\Http\Controllers\PenilaianController@store');
    Route::post('/store-penilaian_ami1', 'App\Http\Controllers\PenilaianController@store1');
    Route::post('/store-penilaian_ami2', 'App\Http\Controllers\PenilaianController@store2');
    Route::post('/store-penilaian_ami3', 'App\Http\Controllers\PenilaianController@store3');
    Route::post('/update-penilaian_ami', 'App\Http\Controllers\PenilaianController@update');
    Route::post('/delete-penilaian_ami', 'App\Http\Controllers\PenilaianController@delete');

    // RECORD TEMUAN
    Route::get('/record_temuan/{jadwal_ami_id}', 'App\Http\Controllers\RecordTemuanController@index');
    Route::get('/data-record_temuan/{jadwal_ami_id}', 'App\Http\Controllers\RecordTemuanController@data');
    Route::post('/store-record_temuan', 'App\Http\Controllers\RecordTemuanController@store');
    Route::post('/update-record_temuan', 'App\Http\Controllers\RecordTemuanController@update');
    Route::post('/delete-record_temuan', 'App\Http\Controllers\RecordTemuanController@delete');

    // LAPORAN AMI
    Route::get('/laporan_ami', 'App\Http\Controllers\LaporanController@index');
    Route::get('/laporan_ami/{id}', 'App\Http\Controllers\LaporanController@detail');
    Route::get('/laporan_ami/{id}/{id_komponen}', 'App\Http\Controllers\LaporanController@detailKomponen');
    Route::get('/data-laporan_ami/{id}', 'App\Http\Controllers\LaporanController@data');
    Route::get('/data-laporan_ami/{id}/{id_komponen}', 'App\Http\Controllers\LaporanController@dataKomponen');
    Route::get('/ba_ami/{jadwalami}', 'App\Http\Controllers\LaporanController@listba');
    Route::get('/ba_cetak/{jadwalami}', 'App\Http\Controllers\LaporanController@ba_cetak');
    Route::post('/update_ba', 'App\Http\Controllers\LaporanController@updateBA');
    Route::post('/update_ba_admin', 'App\Http\Controllers\LaporanController@updateBAAdmin');
});

//LOGOUT
Route::get('/logout', function () {
    Auth::logout();
    return redirect('login');
})->name('logout');
<?php

namespace App\Http\Controllers;

use App\Models\JadwalAmi;
use App\Models\RecordTemuan;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use Auth;
use Illuminate\Support\Facades\Validator;

class RecordTemuanController extends Controller
{
    public function index($jadwal_ami_id)
    {
        $judul= DB::table('jadwal_amis')->where('id',$jadwal_ami_id)->first();
        $getKontak = User::find($judul->pic_auditee);
        
        return view('backend.record_temuans.index', compact('jadwal_ami_id','judul','getKontak'));
    }

    public function data($jadwal_ami_id)
    {

        $record_temuans = DB::table('record_temuans')
        ->leftJoin('jadwal_amis', 'jadwal_amis.id', 'record_temuans.jadwal_ami_id')
        ->where('record_temuans.jadwal_ami_id', $jadwal_ami_id);

        $record_temuans = $record_temuans->get();


        return response()->json(['data' => $record_temuans]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'jadwal_ami_id'   => 'required',
            'no_hp'   => 'required',
            'isi_keterangan'   => 'required',
            'tanggal_input'   => 'required',
        ]);

        if ($validator->fails()) {
            $data = [
                'responCode'    => 0,
                'respon'        => $validator->errors()
            ];
        } else {
            $data = RecordTemuan::create([
                'jadwal_ami_id'   => $request->jadwal_ami_id,
                'no_hp'  => $request->no_hp,
                'isi_keterangan'       => $request->isi_keterangan,
                'tanggal_input'       => $request->tanggal_input,
            ]);

            if($request->kirim_wa == "Ya") {
                $getJadwal = JadwalAmi::find($request->jadwal_ami_id);
                $getKontak = User::find($request->pic_auditee);

                sendTemuan($request->no_hp, $request->isi_keterangan);
            }

            $data = [
                'responCode'    => 1,
                'respon'        => 'Data Sukses Ditambah'
            ];
        }

        return response()->json($data);
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id'    => 'required'
        ]);

        if ($validator->fails()) {
            $data = [
                'responCode'    => 0,
                'respon'        => $validator->errors()
            ];
        } else {

            $record_temuans = RecordTemuan::find($request->id);
            $data = $record_temuans->update([
                'nama_kurikulum'   => $request->nama_kurikulum,
                'jenis_instrumen'  => $request->jenis_instrumen,
                'is_aktif'   => $request->is_aktif,
                'tanggal_input'       => $request->tanggal_input,
            ]);

            $data = [
                'responCode'    => 1,
                'respon'        => 'Data Sukses Disimpan'
            ];
        }

        return response()->json($data);
    }

    public function delete(Request $request)
    {

        $data = RecordTemuan::find($request->id)->delete();

        $data = [
            'responCode'    => 1,
            'respon'        => 'Data Sukses Dihapus'
        ];

        return response()->json($data);
    }
}

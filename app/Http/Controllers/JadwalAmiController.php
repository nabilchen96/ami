<?php

namespace App\Http\Controllers;

use App\Models\JadwalAmi;
use App\Models\KurikulumInstrumen;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use Auth;
use Illuminate\Support\Facades\Validator;

class JadwalAmiController extends Controller
{
    public function index()
    {
        $auditors = User::where('role', 'Auditor')->get();
        $kurikulums = KurikulumInstrumen::where('is_aktif', '1')->get();
        return view('backend.jadwal_ami.index', [
            'auditors' => $auditors,
            'kurikulums' => $kurikulums
        ]);
    }

    public function data()
    {

        $jadwal_amis = DB::table('jadwal_amis')
            ->select('jadwal_amis.*', 'users.name as name', 'auditor1.name as auditor1', 'auditor2.name as auditor2', 'auditor3.name as auditor3', 'kurikulum_instrumens.nama_kurikulum')
            ->leftJoin('users as users', 'users.id', 'jadwal_amis.input_oleh')
            ->leftJoin('users as auditor1', 'auditor1.id', 'jadwal_amis.auditor_satu')
            ->leftJoin('users as auditor2', 'auditor2.id', 'jadwal_amis.auditor_dua')
            ->leftJoin('users as auditor3', 'auditor3.id', 'jadwal_amis.auditor_tiga')
            ->leftJoin('kurikulum_instrumens', 'kurikulum_instrumens.id', 'jadwal_amis.kurikulum_instrumen_id');

        $jadwal_amis = $jadwal_amis->get();

        return response()->json(['data' => $jadwal_amis]);
    }

    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'judul'   => 'required',
        ]);

        if ($validator->fails()) {
            $data = [
                'responCode'    => 0,
                'respon'        => $validator->errors()
            ];
        } else {

            $cekDup = $this->cekDuplikatAuditor($request->auditor_satu, $request->auditor_dua, $request->auditor_tiga);

            if ($cekDup) {
                $data = [
                    'responCode'    => 2,
                    'respon'        => 'Auditor tidak boleh duplikat!'
                ];
            } else {
                $data = JadwalAmi::create([
                    'judul'   => $request->judul,
                    'priode'   => $request->priode,
                    'prodi'   => $request->prodi,
                    'tgl_awal_upload'   => $request->tgl_awal_upload,
                    'tgl_akhir_upload'   => $request->tgl_akhir_upload,
                    'auditor_satu'   => $request->auditor_satu,
                    'auditor_dua'   => $request->auditor_dua,
                    'auditor_tiga'   => $request->auditor_tiga,
                    'kurikulum_instrumen_id'   => $request->kurikulum_instrumen_id,
                    'status_aktif'   => $request->status_aktif,
                    'input_oleh'       => Auth::user()->id,
                ]);

                $data = [
                    'responCode'    => 1,
                    'respon'        => 'Data Sukses Ditambah'
                ];
            }
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

            $cekDup = $this->cekDuplikatAuditor($request->auditor_satu, $request->auditor_dua, $request->auditor_tiga);

            if ($cekDup) {
                $data = [
                    'responCode'    => 2,
                    'respon'        => 'Auditor tidak boleh duplikat!'
                ];
            } else {
                $jadwal_amis = JadwalAmi::find($request->id);
                $data = $jadwal_amis->update([
                    'judul'   => $request->judul,
                    'priode'   => $request->priode,
                    'prodi'   => $request->prodi,
                    'tgl_awal_upload'   => $request->tgl_awal_upload,
                    'tgl_akhir_upload'   => $request->tgl_akhir_upload,
                    'auditor_satu'   => $request->auditor_satu,
                    'auditor_dua'   => $request->auditor_dua,
                    'auditor_tiga'   => $request->auditor_tiga,
                    'kurikulum_instrumen_id'   => $request->kurikulum_instrumen_id,
                    'status_aktif'   => $request->status_aktif,
                    'input_oleh'       => Auth::user()->id,
                ]);

                $data = [
                    'responCode'    => 1,
                    'respon'        => 'Data Sukses Disimpan'
                ];
            }
        }

        return response()->json($data);
    }

    function cekDuplikatAuditor($a1, $a2, $a3)
    {
        if ($a1 == $a2 || $a1 == $a3) {
            return true;
        } else if ($a2 == $a3) {
            return true;
        } else {
            return false;
        }
    }

    public function delete(Request $request)
    {

        $data = JadwalAmi::find($request->id)->delete();

        $data = [
            'responCode'    => 1,
            'respon'        => 'Data Sukses Dihapus'
        ];

        return response()->json($data);
    }
}

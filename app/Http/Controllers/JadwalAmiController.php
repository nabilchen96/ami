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
        $audites = User::where('role', 'Auditee')->get();
        $kurikulums = KurikulumInstrumen::where('is_aktif', '1')->get();
        if (Auth::user()->role == "Admin") {
            return view('backend.jadwal_ami.index', [
                'auditors' => $auditors,
                'audites' => $audites,
                'kurikulums' => $kurikulums
            ]);
        } else if (Auth::user()->role == "Auditee" || Auth::user()->role == "Auditor") {
            return view('backend.jadwal_ami.index_auditee', [
                'auditors' => $auditors,
                'audites' => $audites,
                'kurikulums' => $kurikulums
            ]);
        }
    }

    public function data()
    {
        if (Auth::user()->role == "Admin") {
            $jadwal_amis = DB::table('jadwal_amis')
                ->select('jadwal_amis.*', 'users.name as name', 'auditor1.name as auditor1', 'auditor2.name as auditor2', 'auditor3.name as auditor3', 'pic_auditee.name as nama_pic_auditee', 'pic_auditee2.name as nama_pic_2', 'kurikulum_instrumens.nama_kurikulum')
                ->leftJoin('users as users', 'users.id', 'jadwal_amis.input_oleh')
                ->leftJoin('users as auditor1', 'auditor1.id', 'jadwal_amis.auditor_satu')
                ->leftJoin('users as auditor2', 'auditor2.id', 'jadwal_amis.auditor_dua')
                ->leftJoin('users as auditor3', 'auditor3.id', 'jadwal_amis.auditor_tiga')
                ->leftJoin('users as pic_auditee', 'pic_auditee.id', 'jadwal_amis.pic_auditee')
                ->leftJoin('users as pic_auditee2', 'pic_auditee2.id', 'jadwal_amis.pic_auditee2')
                ->leftJoin('kurikulum_instrumens', 'kurikulum_instrumens.id', 'jadwal_amis.kurikulum_instrumen_id');
        } else if (Auth::user()->role == "Auditor") {
            $jadwal_amis = DB::table('jadwal_amis')
                ->select('jadwal_amis.*', 'users.name as name', 'auditor1.name as auditor1', 'auditor2.name as auditor2', 'auditor3.name as auditor3', 'pic_auditee.name as nama_pic_auditee','pic_auditee2.name as nama_pic_2', 'kurikulum_instrumens.nama_kurikulum')
                ->leftJoin('users as users', 'users.id', 'jadwal_amis.input_oleh')
                ->leftJoin('users as auditor1', 'auditor1.id', 'jadwal_amis.auditor_satu')
                ->leftJoin('users as auditor2', 'auditor2.id', 'jadwal_amis.auditor_dua')
                ->leftJoin('users as auditor3', 'auditor3.id', 'jadwal_amis.auditor_tiga')
                ->leftJoin('users as pic_auditee', 'pic_auditee.id', 'jadwal_amis.pic_auditee')
                ->leftJoin('users as pic_auditee2', 'pic_auditee2.id', 'jadwal_amis.pic_auditee2')
                ->leftJoin('kurikulum_instrumens', 'kurikulum_instrumens.id', 'jadwal_amis.kurikulum_instrumen_id')
                ->where('auditor_satu', Auth::user()->id)
                ->orWhere('auditor_dua', Auth::user()->id)
                ->orWhere('auditor_tiga', Auth::user()->id);
        } else if (Auth::user()->role == "Auditee") {
            $jadwal_amis = DB::table('jadwal_amis')
                ->select('jadwal_amis.*', 'users.name as name', 'auditor1.name as auditor1', 'auditor2.name as auditor2', 'auditor3.name as auditor3', 'pic_auditee.name as nama_pic_auditee', 'pic_auditee2.name as nama_pic_2', 'kurikulum_instrumens.nama_kurikulum')
                ->leftJoin('users as users', 'users.id', 'jadwal_amis.input_oleh')
                ->leftJoin('users as auditor1', 'auditor1.id', 'jadwal_amis.auditor_satu')
                ->leftJoin('users as auditor2', 'auditor2.id', 'jadwal_amis.auditor_dua')
                ->leftJoin('users as auditor3', 'auditor3.id', 'jadwal_amis.auditor_tiga')
                ->leftJoin('users as pic_auditee', 'pic_auditee.id', 'jadwal_amis.pic_auditee')
                ->leftJoin('users as pic_auditee2', 'pic_auditee2.id', 'jadwal_amis.pic_auditee2')
                ->leftJoin('kurikulum_instrumens', 'kurikulum_instrumens.id', 'jadwal_amis.kurikulum_instrumen_id')
                ->where('pic_auditee', Auth::user()->id)
                ->orwhere('pic_auditee2', Auth::user()->id);
        }


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
            $cekPIC = $this->cekDuplikatPIC($request->pic_auditee, $request->pic_auditee2);

            if ($cekDup) {
                $data = [
                    'responCode'    => 2,
                    'respon'        => 'Auditor tidak boleh duplikat!'
                ];
            } elseif($cekPIC){
                $data = [
                    'responCode'    => 2,
                    'respon'        => 'PIC Auditee tidak boleh duplikat!'
                ];
            }  else {
                $data = JadwalAmi::create([
                    'judul'   => $request->judul,
                    'priode'   => $request->priode,
                    'prodi'   => $request->prodi,
                    'tgl_awal_upload'   => $request->tgl_awal_upload,
                    'tgl_akhir_upload'   => $request->tgl_akhir_upload,
                    'tgl_awal_penilaian'   => $request->tgl_awal_penilaian,
                    'tgl_akhir_penilaian'   => $request->tgl_akhir_penilaian,
                    'auditor_satu'   => $request->auditor_satu,
                    'auditor_dua'   => $request->auditor_dua,
                    'auditor_tiga'   => $request->auditor_tiga,
                    'pic_auditee'   => $request->pic_auditee,
                    'pic_auditee2'   => $request->pic_auditee2,
                    // 'link_upload_dokumen'   => $request->link_upload_dokumen,
                    'kurikulum_instrumen_id'   => $request->kurikulum_instrumen_id,
                    'status_aktif'   => $request->status_aktif,
                    'input_oleh'       => Auth::user()->id,
                ]);

                $getKontak = User::find($request->pic_auditee);

                if ($request->kirim_wa == "1") {
                    sendWAJadwal($getKontak->nohp, $request->judul, $request->tgl_awal_upload, $request->tgl_akhir_upload);
                }

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
            $cekPIC = $this->cekDuplikatPIC($request->pic_auditee, $request->pic_auditee2);

            if ($cekDup) {
                $data = [
                    'responCode'    => 2,
                    'respon'        => 'Auditor tidak boleh duplikat!'
                ];
            } elseif($cekPIC){
                $data = [
                    'responCode'    => 2,
                    'respon'        => 'PIC Auditee tidak boleh duplikat!'
                ];
            } else {
                $jadwal_amis = JadwalAmi::find($request->id);
                $data = $jadwal_amis->update([
                    'judul'   => $request->judul,
                    'priode'   => $request->priode,
                    'prodi'   => $request->prodi,
                    'tgl_awal_upload'   => $request->tgl_awal_upload,
                    'tgl_akhir_upload'   => $request->tgl_akhir_upload,
                    'tgl_awal_penilaian'   => $request->tgl_awal_penilaian,
                    'tgl_akhir_penilaian'   => $request->tgl_akhir_penilaian,
                    'auditor_satu'   => $request->auditor_satu,
                    'auditor_dua'   => $request->auditor_dua,
                    'auditor_tiga'   => $request->auditor_tiga,
                    'pic_auditee'   => $request->pic_auditee,
                    'pic_auditee2'   => $request->pic_auditee2,
                    // 'link_upload_dokumen'   => $request->link_upload_dokumen,
                    'kurikulum_instrumen_id'   => $request->kurikulum_instrumen_id,
                    'status_aktif'   => $request->status_aktif,
                    'input_oleh'       => Auth::user()->id,
                ]);

                $getKontak = User::find($request->pic_auditee);

                if ($request->kirim_wa == "1") {
                    sendWAJadwal($getKontak->nohp, $request->judul, $request->tgl_awal_upload, $request->tgl_akhir_upload);
                }

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

    function cekDuplikatPIC($a1, $a2)
    {
        if ($a1 == $a2) {
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

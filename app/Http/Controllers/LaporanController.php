<?php

namespace App\Http\Controllers;

use App\Models\BeritaAcara;
use App\Models\JadwalAmi;
use App\Models\Jawaban;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\KurikulumInstrumen;
use DB;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LaporanController extends Controller
{
    public function index()
    {
        $auditors = User::where('role', 'Auditor')->get();
        $kurikulums = KurikulumInstrumen::where('is_aktif', '1')->get();

        return view('backend.laporan_ami.index', [
            'auditors' => $auditors,
            'kurikulums' => $kurikulums,
        ]);
    }

    public function listba($jadwal_ami_id)
    {
        $auditors = User::where('role', 'Auditor')->get();
        $kurikulums = KurikulumInstrumen::where('is_aktif', '1')->get();
        $jadwal = JadwalAmi::find($jadwal_ami_id);
        $dataBA = BeritaAcara::where('jadwal_ami_id', $jadwal_ami_id)->first();
        $getUserA1 = User::where('id',@$jadwal->auditor_satu)->first();

        if(Auth::user()->role == "Auditee") {
            if(!@$dataBA->ttd_auditor){
                return redirect()->back()->with(
                    [
                        'message' => "Berita Acara belum diisi Auditor"
                    ]
                );
            }
            
        }

        return view('backend.laporan_ami.ba', [
            'auditors' => $auditors,
            'kurikulums' => $kurikulums,
            'jadwal' => $jadwal,
            'dataBA' => $dataBA,
            'getUserA1' => $getUserA1,
        ]);
    }

    public function ba_cetak($jadwal_ami_id)
    {
        $auditors = User::where('role', 'Auditor')->get();
        $kurikulums = KurikulumInstrumen::where('is_aktif', '1')->get();
        $jadwal = JadwalAmi::find($jadwal_ami_id);
        $dataBA = BeritaAcara::where('jadwal_ami_id', $jadwal_ami_id)->first();
        $getUser = User::find(@$dataBA->lead_auditor);
        return view('backend.laporan_ami.ba_cetak', [
            'auditors' => $auditors,
            'kurikulums' => $kurikulums,
            'jadwal' => $jadwal,
            'getUser' => @$getUser,
            'dataBA' => $dataBA,
        ]);
    }

    public function updateBA(Request $request)
    {
        if (Auth::user()->role == "Auditor") {
            // validasi data
            $request->validate([
                'ttd_auditor' => 'required|image|file|max:2048', // 1mb = 1024kb, 2mb = 2048kb
            ]);
            $get = BeritaAcara::where('jadwal_ami_id', $request->jadwal_ami_id)->first();
            if ($get) {
                if ($request->file('ttd_auditor')) {
                    if ($get->ttd_auditor) {
                        // delete terlebih dahulu gambar lama
                        Storage::delete($get->ttd_auditor);
                    }

                    // upload file yg baru
                    $pathFile = $request->file('ttd_auditor')->store('ttd_auditor');
                } else {
                    $pathFile = $get->ttd_auditor;
                }
            } else {
                if ($request->file('ttd_auditor')) {
                    // upload file yg baru
                    $pathFile = $request->file('ttd_auditor')->store('ttd_auditor');
                }
            }
            $update = BeritaAcara::UpdateOrcreate(
                [
                    'jadwal_ami_id' => $request->jadwal_ami_id,
                ],
                [
                    'jadwal_ami_id' => $request->jadwal_ami_id,
                    'lead_auditor' => $request->lead_auditor,
                    'nama_auditee' => $request->nama_auditee,
                    'nip_auditee' => $request->nip_auditee,
                    'isi_ba' => $request->isi_ba,
                    'input_id' => Auth::user()->id,
                    'ttd_auditor' => $pathFile,
                    'tgl_awal' => $request->tgl_awal,
                    'tgl_akhir' => $request->tgl_akhir,
                    'is_publish' => $request->is_publish,
                ]
            );
        } else if (Auth::user()->role == "Auditee") {
            // validasi data
            $request->validate([
                'ttd_auditee' => 'required|image|file|max:2048', // 1mb = 1024kb, 2mb = 2048kb
            ]);
            $get = BeritaAcara::where('jadwal_ami_id', $request->jadwal_ami_id)->first();
            if ($get) {
                if ($request->file('ttd_auditee')) {
                    if ($get->ttd_auditee) {
                        // delete terlebih dahulu gambar lama
                        Storage::delete($get->ttd_auditee);
                    }

                    // upload file yg baru
                    $pathFile = $request->file('ttd_auditee')->store('ttd_auditee');
                } else {
                    $pathFile = $get->ttd_auditee;
                }
            } else {
                if ($request->file('ttd_auditee')) {
                    // upload file yg baru
                    $pathFile = $request->file('ttd_auditee')->store('ttd_auditee');
                }
            }
            $update = BeritaAcara::UpdateOrcreate(
                [
                    'jadwal_ami_id' => $request->jadwal_ami_id,
                ],
                [
                    'ttd_auditee' => $pathFile,
                ]
            );
        }


        if ($update) {
            return redirect()->back()->with([
                'success' => "Data Berhasil diupdate"
            ]);
        } else {
            return redirect()->back()->with([
                'failed' => "Data Gagal diupdate"
            ]);
        }
    }

    public function updateBAAdmin(Request $request)
    {
        // dd($request->all());
        $update = BeritaAcara::UpdateOrcreate(
            [
                'jadwal_ami_id' => $request->jadwal_ami_id,
            ],
            [
                'jadwal_ami_id' => $request->jadwal_ami_id,
                'nomor_surat' => $request->nomor_surat,
            ]
        );

        if ($update) {
            return redirect()->back()->with([
                'success' => "Data Berhasil diupdate"
            ]);
        } else {
            return redirect()->back()->with([
                'failed' => "Data Gagal diupdate"
            ]);
        }
    }

    public function detail($id)
    {
        $data = DB::table('jadwal_amis as ja')
            ->leftjoin('kurikulum_instrumens as ki', 'ki.id', '=', 'ja.kurikulum_instrumen_id')
            ->leftjoin('butir_instrumens as bi', 'bi.kurikulum_instrumen_id', '=', 'ki.id')
            ->leftjoin('grup_instrumens as gi', 'gi.id', '=', 'bi.grup_instrumen_id')
            ->leftjoin('sub_grups as sg', 'sg.id', '=', 'bi.sub_grup_id')
            ->leftjoin('jawabans as j', function ($join) {
                $join->on('j.butir_instrumen_id', '=', 'bi.id')
                    ->whereColumn('j.jadwal_ami_id', '=', 'ja.id'); // Make sure to match jadwal_ami_id
            })
            ->select(
                'ja.id as jadwal_ami_id',
                'bi.grup_instrumen_id',
                'bi.kode_instrumen',
                'gi.nama_grup_instrumen',
                DB::raw('AVG(j.skor) as rata_rata')
            )
            ->where('ja.id', $id)
            ->groupBy('bi.grup_instrumen_id')
            ->get();


        // dd($data);

        $jadwal_amis = DB::table('jadwal_amis')
            ->select('jadwal_amis.*', 'users.name as name', 'auditor1.name as auditor1', 'auditor2.name as auditor2', 'auditor3.name as auditor3', 'kurikulum_instrumens.nama_kurikulum')
            ->leftJoin('users as users', 'users.id', 'jadwal_amis.input_oleh')
            ->leftJoin('users as auditor1', 'auditor1.id', 'jadwal_amis.auditor_satu')
            ->leftJoin('users as auditor2', 'auditor2.id', 'jadwal_amis.auditor_dua')
            ->leftJoin('users as auditor3', 'auditor3.id', 'jadwal_amis.auditor_tiga')
            ->leftJoin('kurikulum_instrumens', 'kurikulum_instrumens.id', 'jadwal_amis.kurikulum_instrumen_id')
            ->where('jadwal_amis.id', $id);


        $jadwal_amis = $jadwal_amis->first();

        $getAVG = DB::table('jawabans')->select(DB::raw('AVG(skor) as rata_rata'))->where('jadwal_ami_id', $id)->first();
        // dd($getAVG->rata_rata);

        return view('backend.laporan_ami.detail', [
            'data' => $data,
            'jadwal' => $jadwal_amis,
            'rata2' => $getAVG->rata_rata,
        ]);
    }

    public function detailKomponen($id, $id_komponen)
    {

        // dd($id_komponen);

        $data = DB::table('jadwal_amis as ja')
            ->leftjoin('kurikulum_instrumens as ki', 'ki.id', '=', 'ja.kurikulum_instrumen_id')
            ->leftjoin('butir_instrumens as bi', 'bi.kurikulum_instrumen_id', '=', 'ki.id')
            ->leftjoin('grup_instrumens as gi', 'gi.id', '=', 'bi.grup_instrumen_id')
            ->leftjoin('sub_grups as sg', 'sg.id', '=', 'bi.sub_grup_id')
            ->leftjoin('jawabans as j', function ($join) {
                $join->on('j.butir_instrumen_id', '=', 'bi.id')
                    ->whereColumn('j.jadwal_ami_id', '=', 'ja.id'); // Make sure to match jadwal_ami_id
            })
            ->select(
                'ja.id as jadwal_ami_id',
                'ja.kurikulum_instrumen_id',
                'bi.nama_instrumen',
                'bi.id as butir_instrumen_id',
                'bi.grup_instrumen_id',
                'bi.kode_instrumen',
                'bi.keterangan',
                'gi.nama_grup_instrumen',
                'sg.nama_sub_grup',
                'sg.id as sub_grup_id',
                'j.skor',
                'j.skor_persen',
            )
            ->where('ja.id', $id)
            ->where('bi.grup_instrumen_id', $id_komponen)
            ->get();

        // dd($data);


        // dd($data);

        $jadwal_amis = DB::table('jadwal_amis')
            ->select('jadwal_amis.*', 'users.name as name', 'auditor1.name as auditor1', 'auditor2.name as auditor2', 'auditor3.name as auditor3', 'kurikulum_instrumens.nama_kurikulum')
            ->leftJoin('users as users', 'users.id', 'jadwal_amis.input_oleh')
            ->leftJoin('users as auditor1', 'auditor1.id', 'jadwal_amis.auditor_satu')
            ->leftJoin('users as auditor2', 'auditor2.id', 'jadwal_amis.auditor_dua')
            ->leftJoin('users as auditor3', 'auditor3.id', 'jadwal_amis.auditor_tiga')
            ->leftJoin('kurikulum_instrumens', 'kurikulum_instrumens.id', 'jadwal_amis.kurikulum_instrumen_id')
            ->where('jadwal_amis.id', $id);

        $listJawaban = DB::table('jawabans')->where('jadwal_ami_id', $id )->where('grup_instrumen_id',$id_komponen)
        ->leftJoin('sub_butir_instrumens','sub_butir_instrumens.id','jawabans.sub_butir_instrumen_id')
        ->select(
            'jawabans.*',
            'sub_butir_instrumens.nama_sub_butir',
        )
        ->get();

        
        $jadwal_amis = $jadwal_amis->first();

        $getAVG = DB::table('jawabans')->select(DB::raw('AVG(skor) as rata_rata'))->where('jadwal_ami_id', $id)->where('grup_instrumen_id', $id_komponen)->first();
        // dd($getAVG->rata_rata);

        return view('backend.laporan_ami.komponen', [
            'data' => $data,
            'listJawaban' => $listJawaban,
            'jadwal' => $jadwal_amis,
            'rata2' => $getAVG->rata_rata,
        ]);
    }

    public function data($id)
    {
        $data = DB::table('jadwal_amis as ja')
            ->leftjoin('kurikulum_instrumens as ki', 'ki.id', '=', 'ja.kurikulum_instrumen_id')
            ->leftjoin('butir_instrumens as bi', 'bi.kurikulum_instrumen_id', '=', 'ki.id')
            ->leftjoin('grup_instrumens as gi', 'gi.id', '=', 'bi.grup_instrumen_id')
            ->leftjoin('sub_grups as sg', 'sg.id', '=', 'bi.sub_grup_id')
            ->leftjoin('jawabans as j', function ($join) {
                $join->on('j.butir_instrumen_id', '=', 'bi.id')
                    ->whereColumn('j.jadwal_ami_id', '=', 'ja.id'); // Make sure to match jadwal_ami_id
            })
            ->select(
                'ja.id as jadwal_ami_id',
                'bi.grup_instrumen_id',
                'gi.nama_grup_instrumen',
                DB::raw('ROUND(AVG(j.skor),2) as rata_rata')
            )
            ->where('ja.id', $id)
            ->groupBy('bi.grup_instrumen_id')
            ->get();

        return response()->json($data);
    }

    public function dataKomponen($id, $id_komponen)
    {
        $data = DB::table('jadwal_amis as ja')
            ->leftjoin('kurikulum_instrumens as ki', 'ki.id', '=', 'ja.kurikulum_instrumen_id')
            ->leftjoin('butir_instrumens as bi', 'bi.kurikulum_instrumen_id', '=', 'ki.id')
            ->leftjoin('grup_instrumens as gi', 'gi.id', '=', 'bi.grup_instrumen_id')
            ->leftjoin('sub_grups as sg', 'sg.id', '=', 'bi.sub_grup_id')
            ->leftjoin('jawabans as j', function ($join) {
                $join->on('j.butir_instrumen_id', '=', 'bi.id')
                    ->whereColumn('j.jadwal_ami_id', '=', 'ja.id'); // Make sure to match jadwal_ami_id
            })
            ->select(
                'ja.id as jadwal_ami_id',
                'ja.kurikulum_instrumen_id',
                'bi.nama_instrumen',
                'bi.id as butir_instrumen_id',
                'bi.grup_instrumen_id',
                'bi.kode_instrumen',
                'gi.nama_grup_instrumen',
                'sg.nama_sub_grup',
                'j.skor',
            )
            ->where('ja.id', $id)
            ->where('bi.grup_instrumen_id', $id_komponen)
            ->get();

        // dd($data);

        return response()->json($data);
    }
}

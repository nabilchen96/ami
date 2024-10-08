<?php

namespace App\Http\Controllers;

use App\Models\Jawaban;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\KurikulumInstrumen;
use DB;
use Auth;
use Illuminate\Support\Facades\Validator;

class PenilaianController extends Controller
{
    public function index()
    {
        $auditors = User::where('role', 'Auditor')->get();
        $kurikulums = KurikulumInstrumen::where('is_aktif', '1')->get();

        return view('backend.penilaian_ami.index', [
            'auditors' => $auditors,
            'kurikulums' => $kurikulums,
        ]);
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
                'j.skor_persen1',
                'j.skor_persen2',
                'j.skor_persen3',
            )
            ->where('ja.id', $id)
            ->groupBy('bi.id')
            ->get();


        // dd($data);

        $jadwal_amis = DB::table('jadwal_amis')
            ->select('jadwal_amis.*', 'users.name as name', 'auditor1.name as auditor1', 'auditor2.name as auditor2', 'auditor3.name as auditor3', 'kurikulum_instrumens.nama_kurikulum', 'kurikulum_instrumens.jenis_instrumen')
            ->leftJoin('users as users', 'users.id', 'jadwal_amis.input_oleh')
            ->leftJoin('users as auditor1', 'auditor1.id', 'jadwal_amis.auditor_satu')
            ->leftJoin('users as auditor2', 'auditor2.id', 'jadwal_amis.auditor_dua')
            ->leftJoin('users as auditor3', 'auditor3.id', 'jadwal_amis.auditor_tiga')
            ->leftJoin('kurikulum_instrumens', 'kurikulum_instrumens.id', 'jadwal_amis.kurikulum_instrumen_id')
            ->where('jadwal_amis.id', $id);


        $jadwal_amis = $jadwal_amis->first();

        return view('backend.penilaian_ami.detail', [
            'data' => $data,
            'jadwal' => $jadwal_amis
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
                'ja.kurikulum_instrumen_id',
                'bi.nama_instrumen',
                'bi.id as butir_instrumen_id',
                'bi.grup_instrumen_id',
                'bi.kode_instrumen',
                'gi.nama_grup_instrumen',
                'sg.nama_sub_grup',
                'sg.id as sub_grup_id',
                'j.skor'
            )
            ->where('ja.id', $id)
            ->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {

        if ($request->jenis_instrumen == "SN-DIKTI") {
            $skor = $request->skor_persen;
            if ($skor == 100) {
                $nilainya = 4;
            } elseif ($skor >= 66.0 && $skor <= 99.99) {
                $nilainya = 3;
            } elseif ($skor >= 33.0 && $skor <= 65.99) {
                $nilainya = 2;
            } elseif ($skor >= 0.0 && $skor <= 32.99) {
                $nilainya = 1;
            } else {
                $nilainya = 0;
            }

            Jawaban::UpdateOrcreate(
                [
                    'jadwal_ami_id' => $request->jadwal_ami_id,
                    'butir_instrumen_id' => $request->butir_instrumen_id
                ],
                [
                    'jadwal_ami_id' => $request->jadwal_ami_id,
                    'butir_instrumen_id' => $request->butir_instrumen_id,
                    'grup_instrumen_id' => $request->grup_instrumen_id,
                    'sub_grup_id' => $request->sub_grup_id,
                    'kurikulum_instrumen_id' => $request->kurikulum_instrumen_id,
                    'skor' => $nilainya,
                    'skor_persen' => $skor,
                    'create_oleh' => Auth::id()
                ]
            );
        } else {
            $skor = $request->skor;
            $nilainya = $skor;

            Jawaban::UpdateOrcreate(
                [
                    'jadwal_ami_id' => $request->jadwal_ami_id,
                    'butir_instrumen_id' => $request->butir_instrumen_id
                ],
                [
                    'jadwal_ami_id' => $request->jadwal_ami_id,
                    'butir_instrumen_id' => $request->butir_instrumen_id,
                    'grup_instrumen_id' => $request->grup_instrumen_id,
                    'sub_grup_id' => $request->sub_grup_id,
                    'kurikulum_instrumen_id' => $request->kurikulum_instrumen_id,
                    'skor' => $nilainya,
                    'create_oleh' => Auth::id()
                ]
            );
        }

        return back();
    }

    public function store1(Request $request)
    {
        // dd($request->all());
        Jawaban::UpdateOrcreate(
            [
                'jadwal_ami_id' => $request->jadwal_ami_id,
                'butir_instrumen_id' => $request->butir_instrumen_id,
                'sub_butir_instrumen_id' => $request->sub_butir_instrumen_id,
            ],
            [
                'jadwal_ami_id' => $request->jadwal_ami_id,
                'butir_instrumen_id' => $request->butir_instrumen_id,
                'sub_butir_instrumen_id' => $request->sub_butir_instrumen_id,
                'grup_instrumen_id' => $request->grup_instrumen_id,
                'sub_grup_id' => $request->sub_grup_id,
                'kurikulum_instrumen_id' => $request->kurikulum_instrumen_id,
                'skor_persen1' => $request->skor_persen1,
                'create_oleh' => Auth::id()
            ]
        );

        return back();
    }

    public function store2(Request $request)
    {
        // dd($request->all());
        Jawaban::UpdateOrcreate(
            [
                'jadwal_ami_id' => $request->jadwal_ami_id,
                'butir_instrumen_id' => $request->butir_instrumen_id,
                'sub_butir_instrumen_id' => $request->sub_butir_instrumen_id,
            ],
            [
                'jadwal_ami_id' => $request->jadwal_ami_id,
                'butir_instrumen_id' => $request->butir_instrumen_id,
                'sub_butir_instrumen_id' => $request->sub_butir_instrumen_id,
                'grup_instrumen_id' => $request->grup_instrumen_id,
                'sub_grup_id' => $request->sub_grup_id,
                'kurikulum_instrumen_id' => $request->kurikulum_instrumen_id,
                'skor_persen2' => $request->skor_persen2,
                'create_oleh' => Auth::id()
            ]
        );

        return back();
    }

    public function store3(Request $request)
    {
       
        $data = Jawaban::where('jadwal_ami_id',$request->jadwal_ami_id)->where('butir_instrumen_id',$request->butir_instrumen_id)->where('sub_butir_instrumen_id',$request->sub_butir_instrumen_id)->first();
        $cekJwb = Jawaban::where('jadwal_ami_id',$request->jadwal_ami_id)->where('butir_instrumen_id',$request->butir_instrumen_id)->where('sub_butir_instrumen_id',$request->sub_butir_instrumen_id)->where('skor_persen1', NULL)->orWhere('skor_persen2', NULL)->first();

        // if(!$data) {
        //     echo "<script language='javascript'>
        //         window.alert('Nilai 1 atau Nilai 2 belum diinput!');
        //         window.history.go(-1)
        //         </script>";
        //     die();
        // }

        // if($cekJwb) {
        //     echo "<script language='javascript'>
        //         window.alert('Nilai 1 atau Nilai 2 belum diinput!');
        //         window.history.go(-1)
        //         </script>";
        //     die();
        // }
        
        $rata = ($data->skor_persen1 + $data->skor_persen2 + $request->skor_persen3 ) / 3;

        if ($rata == 100) {
            $nilainya = 4;
        } elseif ($rata >= 66.0 && $rata <= 99.99) {
            $nilainya = 3;
        } elseif ($rata >= 33.0 && $rata <= 65.99) {
            $nilainya = 2;
        } elseif ($rata >= 0.0 && $rata <= 32.99) {
            $nilainya = 1;
        } else {
            $nilainya = 0;
        }

        Jawaban::UpdateOrcreate(
            [
                'jadwal_ami_id' => $request->jadwal_ami_id,
                'butir_instrumen_id' => $request->butir_instrumen_id,
                'sub_butir_instrumen_id' => $request->sub_butir_instrumen_id,
            ],
            [
                'jadwal_ami_id' => $request->jadwal_ami_id,
                'butir_instrumen_id' => $request->butir_instrumen_id,
                'sub_butir_instrumen_id' => $request->sub_butir_instrumen_id,
                'grup_instrumen_id' => $request->grup_instrumen_id,
                'sub_grup_id' => $request->sub_grup_id,
                'kurikulum_instrumen_id' => $request->kurikulum_instrumen_id,
                'skor_persen3' => $request->skor_persen3,
                'skor_persen' => $rata,
                'skor' => $nilainya,
                'create_oleh' => Auth::id()
            ]
        );

        return back();
    }
}

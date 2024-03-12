<?php

namespace App\Http\Controllers;

use App\Models\ButirInstrumen;
use App\Models\GrupInstrumen;
use App\Models\SubGrup;
use Illuminate\Http\Request;
use DB;
use Auth;
use Illuminate\Support\Facades\Validator;

class ButirInstrumenController extends Controller
{
    public function index($kurikulum_id)
    {
        $grup_instrumen = GrupInstrumen::all();
        $sub_grup = SubGrup::all();

        return view('backend.butir_instrumens.index', [
            'kurikulum_id' => $kurikulum_id,
            'grup_instrumen' => $grup_instrumen,
            'sub_grup' => $sub_grup
        ]);
    }

    public function getSubGrup($idGrup)
    {

        echo json_encode(DB::table('sub_grups')->where('grup_instrumen_id',$idGrup)->get());
    }

    public function data($kurikulum_id)
    {

        $butir_instrumens = DB::table('butir_instrumens')
            ->select('butir_instrumens.*', 'users.name', 'grup_instrumens.nama_grup_instrumen', 'kurikulum_instrumens.nama_kurikulum','sub_grups.nama_sub_grup')
            ->leftJoin('grup_instrumens', 'grup_instrumens.id', 'butir_instrumens.grup_instrumen_id')
            ->leftJoin('sub_grups', 'sub_grups.id', 'butir_instrumens.sub_grup_id')
            ->leftJoin('kurikulum_instrumens', 'kurikulum_instrumens.id', 'butir_instrumens.kurikulum_instrumen_id')
            ->leftJoin('users', 'users.id', 'butir_instrumens.insert_by')
            ->where('butir_instrumens.kurikulum_instrumen_id',$kurikulum_id);

        $butir_instrumens = $butir_instrumens->get();

        return response()->json(['data' => $butir_instrumens]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'kode_instrumen'   => 'required',
            'nama_instrumen'      => 'required',
            'keterangan'    => 'required'
        ]);

        if ($validator->fails()) {
            $data = [
                'responCode'    => 0,
                'respon'        => $validator->errors()
            ];
        } else {
            $data = ButirInstrumen::create([
                'kode_instrumen'   => $request->kode_instrumen,
                'nama_instrumen'   => $request->nama_instrumen,
                'keterangan'    => $request->keterangan,
                'grup_instrumen_id'    => $request->grup_instrumen_id,
                'sub_grup_id'    => $request->sub_grup_id,
                'kurikulum_instrumen_id'    => $request->kurikulum_id,
                'insert_by'            => Auth::user()->id,
            ]);

            $data = [
                'responCode'    => 1,
                'respon'        => 'Data Sukses Ditambah'
            ];
        }

        return response()->json($data);
    }

    public function edit($id)
    {
        $grup_instrumen = GrupInstrumen::all();
        $data = ButirInstrumen::find($id);
        $sub_grup = SubGrup::all();

        return view('backend.butir_instrumens.edit', [
            'data' => $data,
            'grup_instrumen' => $grup_instrumen,
            'sub_grup' => $sub_grup,
        ]);
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

            $butir_instrumens = ButirInstrumen::find($request->id);
            $data = $butir_instrumens->update([
                'kode_instrumen'   => $request->kode_instrumen,
                'nama_instrumen'   => $request->nama_instrumen,
                'grup_instrumen_id' => $request->grup_instrumen_id,
                'sub_grup_id'    => $request->sub_grup_id,
                'keterangan'    => $request->keterangan,
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

        $data = ButirInstrumen::find($request->id)->delete();

        $data = [
            'responCode'    => 1,
            'respon'        => 'Data Sukses Dihapus'
        ];

        return response()->json($data);
    }
}

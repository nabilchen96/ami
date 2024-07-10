<?php

namespace App\Http\Controllers;

use App\Models\KurikulumInstrumen;
use Illuminate\Http\Request;
use DB;
use Auth;
use Illuminate\Support\Facades\Validator;

class KurikulumInstrumenController extends Controller
{
    public function index()
    {
        return view('backend.kurikulum_instrumens.index');
    }

    public function data()
    {

        $kurikulum_instrumens = DB::table('kurikulum_instrumens')
            ->select('kurikulum_instrumens.*', 'users.name')
            ->leftJoin('users', 'users.id', 'kurikulum_instrumens.input_oleh');

        $kurikulum_instrumens = $kurikulum_instrumens->get();


        return response()->json(['data' => $kurikulum_instrumens]);
    }

    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'nama_kurikulum'   => 'required',
        ]);

        if ($validator->fails()) {
            $data = [
                'responCode'    => 0,
                'respon'        => $validator->errors()
            ];
        } else {
            $data = KurikulumInstrumen::create([
                'nama_kurikulum'   => $request->nama_kurikulum,
                'jenis_instrumen'  => $request->jenis_instrumen,
                'input_oleh'       => Auth::user()->id,
            ]);

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

            $kurikulum_instrumens = KurikulumInstrumen::find($request->id);
            $data = $kurikulum_instrumens->update([
                'nama_kurikulum'   => $request->nama_kurikulum,
                'jenis_instrumen'  => $request->jenis_instrumen,
                'is_aktif'   => $request->is_aktif,
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

        $data = KurikulumInstrumen::find($request->id)->delete();

        $data = [
            'responCode'    => 1,
            'respon'        => 'Data Sukses Dihapus'
        ];

        return response()->json($data);
    }
}

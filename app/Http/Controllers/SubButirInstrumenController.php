<?php

namespace App\Http\Controllers;

use App\Models\ButirInstrumen;
use App\Models\SubButirInstrumen;
use Illuminate\Http\Request;
use DB;
use Exception;
use Illuminate\Support\Facades\Validator;
use Auth;

class SubButirInstrumenController extends Controller
{
    public function index($butir_instrumen_id)
    {
        $butir_instrumen = ButirInstrumen::find($butir_instrumen_id);
        $data = SubButirInstrumen::where('butir_instrumen_id', $butir_instrumen_id)->get();
        return view('backend.sub_butir_instrumens.index', [
            'butir_instrumen' => $butir_instrumen,
            'data' => $data,
            'butir_instrumen_id' => $butir_instrumen_id
        ]);
    }

    public function data($butir_instrumen_id)
    {

        $sub_butir_instrumens = DB::table('sub_butir_instrumens')
            ->where('butir_instrumen_id', $butir_instrumen_id);

        $sub_butir_instrumens = $sub_butir_instrumens->get();

        return response()->json(['data' => $sub_butir_instrumens]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'nama_sub_butir'   => 'required',
            'upload_file'      => 'required'
        ]);

        if ($validator->fails()) {
            $data = [
                'responCode'    => 0,
                'respon'        => $validator->errors()
            ];
        } else {
            $data = SubButirInstrumen::create([
                'nama_sub_butir'   => $request->nama_sub_butir,
                'upload_file'   => $request->upload_file,
                'butir_instrumen_id' => $request->butir_instrumen_id,
                'input_by'            => Auth::user()->id,
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

            
                $butir_instrumens = SubButirInstrumen::find($request->id);
                $data = $butir_instrumens->update([
                    'nama_sub_butir'   => $request->nama_sub_butir,
                    'upload_file'   => $request->upload_file
                ]);

                $data = [
                    'responCode'    => 1,
                    'respon'        => 'Data Sukses Disimpan'
                ];

            // dd($data);
        }

        return response()->json($data);
    }

    public function delete(Request $request)
    {

        $data = SubButirInstrumen::find($request->id)->delete();

        $data = [
            'responCode'    => 1,
            'respon'        => 'Data Sukses Dihapus'
        ];

        return response()->json($data);
    }
}

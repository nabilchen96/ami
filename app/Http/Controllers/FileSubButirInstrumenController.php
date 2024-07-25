<?php

namespace App\Http\Controllers;

use App\Models\FileSubButirInstrumen;
use App\Models\SubButirInstrumen;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\Storage;

class FileSubButirInstrumenController extends Controller
{
    public function index($sub_butir_instrumen_id)
    {
        $subbutir = SubButirInstrumen::where('id', $sub_butir_instrumen_id)->first();
        return view('backend.file_sub_butir_instrumens.index', [
            'subbutir' => $subbutir,
            'sub_butir_instrumen_id' => $sub_butir_instrumen_id
        ]);
    }

    public function data($sub_butir_instrumen_id)
    {

        $file_sub_butir_instrumens = DB::table('file_sub_butir_instrumens')
            ->where('sub_butir_instrumen_id', $sub_butir_instrumen_id);

        $file_sub_butir_instrumens = $file_sub_butir_instrumens->get();

        return response()->json(['data' => $file_sub_butir_instrumens]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama_file'      => 'required',
            'tampilkan'      => 'required',
            'file_upload' => 'mimes:pdf,doc,docx|file|max:20480|required',
        ]);

        if ($validator->fails()) {
            $data = [
                'responCode'    => 0,
                'respon'        => $validator->errors()
            ];
        } else {
            if ($request->file('file_upload')) {
                //tentukan folder penyimpanan gambarnya
                $pathFile = $request->file('file_upload')->store('berkas');
            } else {
                $pathFile = null;
            }

            $data = FileSubButirInstrumen::create([
                'nama_file'          => $request->nama_file,
                'tampilkan'          => $request->tampilkan,
                'sub_butir_instrumen_id' => $request->sub_butir_instrumen_id,
                'file_upload'          => $pathFile,
                'upload_by'      => Auth::user()->id,
            ]);

            $data = [
                'responCode'    => 1,
                'respon'        => 'Data Sukses Ditambah'
            ];
        }

        return response()->json($data);
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'nama_file'      => 'required',
            'tampilkan'      => 'required',
            'file_upload' => 'mimes:pdf,doc,docx|file|max:20480|nullable',
        ]);

        if ($validator->fails()) {
            $data = [
                'responCode'    => 0,
                'respon'        => $validator->errors()
            ];
        } else {

            $get = FileSubButirInstrumen::find($request->id);

            if($request->file('file_upload')) {
                if ($get->file_upload) {
                    // delete terlebih dahulu gambar lama
                    Storage::delete($get->file_upload);
                }

                // upload file yg baru
                $pathFile = $request->file('file_upload')->store('berkas');
            } else {
                $pathFile = $get->file_upload;
            }

            $data = $get->update([
                'nama_file'          => $request->nama_file,
                'tampilkan'          => $request->tampilkan,
                'sub_butir_instrumen_id' => $request->sub_butir_instrumen_id,
                'file_upload'          => $pathFile,
            ]);

            $data = [
                'responCode'    => 1,
                'respon'        => 'Data Sukses Diupdate'
            ];
        }

        return response()->json($data);
    }

    public function delete(Request $request)
    {

        $data = FileSubButirInstrumen::find($request->id);
        Storage::delete(@$data->file_upload);
        
        $data->delete();

        $data = [
            'responCode'    => 1,
            'respon'        => 'Data Sukses Dihapus'
        ];

        return response()->json($data);
    }
}

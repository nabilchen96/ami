<?php

namespace App\Http\Controllers;

use App\Models\ButirInstrumen;
use App\Models\SubButirInstrumen;
use Illuminate\Http\Request;
use DB;

class SubButirInstrumenController extends Controller
{
    public function index($butir_instrumen_id)
    {
        $butir_instrumen = ButirInstrumen::find($butir_instrumen_id);
        $data = SubButirInstrumen::where('butir_instrumen_id',$butir_instrumen_id)->get();
        return view('backend.sub_butir_instrumens.index', [
            'butir_instrumen' => $butir_instrumen,
            'data' => $data,
            'butir_instrumen_id' => $butir_instrumen_id
        ]);
    }

    public function data($butir_instrumen_id)
    {

        $sub_butir_instrumens = DB::table('sub_butir_instrumens')
            ->where('butir_instrumen_id',$butir_instrumen_id);

        $sub_butir_instrumens = $sub_butir_instrumens->get();

        return response()->json(['data' => $sub_butir_instrumens]);
    }
}

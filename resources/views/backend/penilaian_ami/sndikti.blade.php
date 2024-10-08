@php
    $user = Auth::user();

    $auditor1 = DB::table('jadwal_amis')
        ->where('id', $jadwal->id)
        ->where('auditor_satu', $user->id)
        ->first();
    $auditor2 = DB::table('jadwal_amis')
        ->where('id', $jadwal->id)
        ->where('auditor_dua', $user->id)
        ->first();
    $auditor3 = DB::table('jadwal_amis')
        ->where('id', $jadwal->id)
        ->where('auditor_tiga', $user->id)
        ->first();

    $today = date('Y-m-d');

@endphp

<div class="table-responsive mt-4">
    <table id="myTable" class="table table-bordered" style="width: 100%;">
        <thead class="bg-primary text-white">
            <tr>
                <th></th>
                <th></th>
                <th>Pertanyaan</th>
                <th>Standar</th>
                <th>Rata-rata (%)</th>
                <th>Nilai (Skala 1-4)</th>

            </tr>
        </thead>
        <tbody>
            @php
                $namagrup = '';
                $namagrupprev = '';
                $namasubgrup = '';
                $namasubgrupprev = '';
                $totalRows = count($data);
            @endphp

            @foreach ($data as $k => $item)
                @php
                    $namagrup = $item->nama_grup_instrumen;
                    $namasubgrup = $item->nama_sub_grup;
                @endphp
                @if ($namagrup != $namagrupprev)
                    <tr style="background: green !important;">
                        <td
                            colspan="{{ Auth::user()->role == 'Admin' || Auth::user()->role == 'Auditor' ? '6' : '6' }} ">
                            <b>{{ $item->nama_grup_instrumen }}</b>
                        </td>
                    </tr>
                @endif
                @if ($item->nama_sub_grup != $namasubgrupprev)
                    @php
                        $groupRowCount = 0;
                        foreach ($data as $row) {
                            if ($row->nama_sub_grup == $item->nama_sub_grup) {
                                $groupRowCount++;
                            }
                        }
                    @endphp
                    <tr>
                        <td rowspan="{{ $groupRowCount }}"><b>{{ $item->nama_sub_grup }}</b>
                        </td>
                        <td>{{ $item->kode_instrumen }}</td>
                        <td>{{ $item->nama_instrumen }}</td>
                        <td>
                            @php
                                $subs = DB::table('sub_butir_instrumens')
                                    ->where('butir_instrumen_id', $item->butir_instrumen_id)
                                    ->get();
                            @endphp

                            <ul>
                                @foreach ($subs as $subss)
                                    @php
                                        $jwb = DB::table('jawabans')
                                            ->where('butir_instrumen_id', $item->butir_instrumen_id)
                                            ->where('sub_butir_instrumen_id', $subss->id)
                                            ->first();

                                    @endphp
                                    <li>{{ $subss->nama_sub_butir }}
                                        @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Auditor')
                                            @if ($subss->upload_file == '1')
                                                @if ($jadwal->tgl_akhir_upload < $today)
                                                    <a class="text-danger"
                                                        href="{{ url('file_subbutir_instrumen/' . $subss->id . '/' . $jadwal->id) }}"
                                                        title="Cek File">Batas Waktu
                                                        Upload telah
                                                        Selesai</a> <br>
                                                @else
                                                    <a href="{{ url('file_subbutir_instrumen/' . $subss->id . '/' . $jadwal->id) }}"
                                                        title="Cek File">Cek File</a> <br>
                                                @endif

                                                <form action="{{ url('store-penilaian_ami1') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="butir_instrumen_id"
                                                        value="{{ $item->butir_instrumen_id }}">
                                                    <input type="hidden" name="grup_instrumen_id"
                                                        value="{{ $item->grup_instrumen_id }}">
                                                    <input type="hidden" name="kurikulum_instrumen_id"
                                                        value="{{ $item->kurikulum_instrumen_id }}">
                                                    <input type="hidden" name="jadwal_ami_id"
                                                        value="{{ $item->jadwal_ami_id }}">
                                                    <input type="hidden" name="sub_grup_id"
                                                        value="{{ $item->sub_grup_id }}">
                                                    <input type="hidden" name="jenis_instrumen"
                                                        value="{{ $jadwal->jenis_instrumen }}">
                                                    <input type="hidden" name="sub_butir_instrumen_id"
                                                        value="{{ $subss->id }}">
                                                    <input type="number" name="skor_persen1"
                                                        value="{{ @$jwb->skor_persen1 }}" class="form-control"
                                                        placeholder="Nilai 1 (%)">
                                                    @if (Auth::user()->role == 'Admin')
                                                        <button class="btn btn-primary btn-block"
                                                            style="border-radius: 10px !important; margin-top:5px">Submit</button>
                                                    @elseif(Auth::user()->role == 'Auditor')
                                                        <button class="btn btn-primary btn-block"
                                                            style="border-radius: 10px !important; margin-top:5px"
                                                            {{ @$auditor1->auditor_satu != @$user->id ? 'disabled' : '' }}>Submit</button>
                                                    @endif
                                                </form>
                                                <hr>
                                                <form action="{{ url('store-penilaian_ami2') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="butir_instrumen_id"
                                                        value="{{ $item->butir_instrumen_id }}">
                                                    <input type="hidden" name="grup_instrumen_id"
                                                        value="{{ $item->grup_instrumen_id }}">
                                                    <input type="hidden" name="kurikulum_instrumen_id"
                                                        value="{{ $item->kurikulum_instrumen_id }}">
                                                    <input type="hidden" name="jadwal_ami_id"
                                                        value="{{ $item->jadwal_ami_id }}">
                                                    <input type="hidden" name="sub_grup_id"
                                                        value="{{ $item->sub_grup_id }}">
                                                    <input type="hidden" name="jenis_instrumen"
                                                        value="{{ $jadwal->jenis_instrumen }}">
                                                    <input type="hidden" name="sub_butir_instrumen_id"
                                                        value="{{ $subss->id }}">
                                                    <input type="number" name="skor_persen2"
                                                        value="{{ @$jwb->skor_persen2 }}" class="form-control"
                                                        placeholder="Nilai 2 (%)">
                                                    @if (Auth::user()->role == 'Admin')
                                                        <button class="btn btn-primary btn-block"
                                                            style="border-radius: 10px !important; margin-top:5px">Submit</button>
                                                    @elseif(Auth::user()->role == 'Auditor')
                                                        <button class="btn btn-primary btn-block"
                                                            style="border-radius: 10px !important; margin-top:5px"
                                                            {{ @$auditor2->auditor_dua != @$user->id ? 'disabled' : '' }}>Submit</button>
                                                    @endif
                                                </form>
                                                <hr>
                                                <form action="{{ url('store-penilaian_ami3') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="butir_instrumen_id"
                                                        value="{{ $item->butir_instrumen_id }}">
                                                    <input type="hidden" name="grup_instrumen_id"
                                                        value="{{ $item->grup_instrumen_id }}">
                                                    <input type="hidden" name="kurikulum_instrumen_id"
                                                        value="{{ $item->kurikulum_instrumen_id }}">
                                                    <input type="hidden" name="jadwal_ami_id"
                                                        value="{{ $item->jadwal_ami_id }}">
                                                    <input type="hidden" name="sub_grup_id"
                                                        value="{{ $item->sub_grup_id }}">
                                                    <input type="hidden" name="jenis_instrumen"
                                                        value="{{ $jadwal->jenis_instrumen }}">
                                                    <input type="hidden" name="sub_butir_instrumen_id"
                                                        value="{{ $subss->id }}">
                                                    <input type="number" name="skor_persen3"
                                                        value="{{ @$jwb->skor_persen3 }}" class="form-control"
                                                        placeholder="Nilai 3 (%)">
                                                    @if (@!$jwb->skor_persen2 || @!$jwb->skor_persen1)
                                                        @if (Auth::user()->role == 'Admin')
                                                            <button class="btn btn-primary btn-block" disabled
                                                                style="border-radius: 10px !important; margin-top:5px">Submit</button>
                                                        @elseif(Auth::user()->role == 'Auditor')
                                                            <button class="btn btn-primary btn-block" disabled
                                                                style="border-radius: 10px !important; margin-top:5px">Submit</button>
                                                        @endif
                                                    @else
                                                        @if (Auth::user()->role == 'Admin')
                                                            <button class="btn btn-primary btn-block"
                                                                style="border-radius: 10px !important; margin-top:5px">Submit</button>
                                                        @elseif(Auth::user()->role == 'Auditor')
                                                            <button class="btn btn-primary btn-block"
                                                                style="border-radius: 10px !important; margin-top:5px"
                                                                {{ @$auditor3->auditor_tiga != @$user->id ? 'disabled' : '' }}>Submit</button>
                                                        @endif
                                                    @endif

                                                </form>

                                                Rata-rata
                                                <input type="text" disabled name=""
                                                    value="{{ @$jwb->skor_persen }} " class="form-control">

                                                Skor (Skala 1 - 4)
                                                <input type="text" disabled name=""
                                                    value="{{ @$jwb->skor }}" class="form-control">
                                                <hr>
                                            @else
                                            @endif
                                        @else
                                        {{-- AUDITEE --}}
                                            @if ($jadwal->tgl_akhir_upload < $today)
                                                <a href="{{ url('file_subbutir_instrumen/' . $subss->id . '/' . $jadwal->id) }}"
                                                    title="Upload File" class="text-danger">Batas Waktu
                                                    Upload telah
                                                    Selesai</a>
                                                <form action="{{ url('store-penilaian_ami1') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="butir_instrumen_id"
                                                        value="{{ $item->butir_instrumen_id }}">
                                                    <input type="hidden" name="grup_instrumen_id"
                                                        value="{{ $item->grup_instrumen_id }}">
                                                    <input type="hidden" name="kurikulum_instrumen_id"
                                                        value="{{ $item->kurikulum_instrumen_id }}">
                                                    <input type="hidden" name="jadwal_ami_id"
                                                        value="{{ $item->jadwal_ami_id }}">
                                                    <input type="hidden" name="sub_grup_id"
                                                        value="{{ $item->sub_grup_id }}">
                                                    <input type="hidden" name="jenis_instrumen"
                                                        value="{{ $jadwal->jenis_instrumen }}">
                                                    <input type="hidden" name="sub_butir_instrumen_id"
                                                        value="{{ $subss->id }}">
                                                    <input type="number" name="skor_persen1" disabled
                                                        value="{{ @$jwb->skor_persen1 }}" class="form-control"
                                                        placeholder="Nilai 1 (%)">
                                                    {{-- <button class="btn btn-primary btn-block"
                                                        style="border-radius: 10px !important; margin-top:5px">Submit</button> --}}
                                                </form>
                                                <hr>
                                                <form action="{{ url('store-penilaian_ami2') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="butir_instrumen_id"
                                                        value="{{ $item->butir_instrumen_id }}">
                                                    <input type="hidden" name="grup_instrumen_id"
                                                        value="{{ $item->grup_instrumen_id }}">
                                                    <input type="hidden" name="kurikulum_instrumen_id"
                                                        value="{{ $item->kurikulum_instrumen_id }}">
                                                    <input type="hidden" name="jadwal_ami_id"
                                                        value="{{ $item->jadwal_ami_id }}">
                                                    <input type="hidden" name="sub_grup_id"
                                                        value="{{ $item->sub_grup_id }}">
                                                    <input type="hidden" name="jenis_instrumen"
                                                        value="{{ $jadwal->jenis_instrumen }}">
                                                    <input type="hidden" name="sub_butir_instrumen_id"
                                                        value="{{ $subss->id }}">
                                                    <input type="number" name="skor_persen2" disabled
                                                        value="{{ @$jwb->skor_persen2 }}" class="form-control"
                                                        placeholder="Nilai 2 (%)">
                                                    {{-- <button class="btn btn-primary btn-block"
                                                        style="border-radius: 10px !important; margin-top:5px">Submit</button> --}}
                                                </form>
                                                <hr>
                                                <form action="{{ url('store-penilaian_ami3') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="butir_instrumen_id"
                                                        value="{{ $item->butir_instrumen_id }}">
                                                    <input type="hidden" name="grup_instrumen_id"
                                                        value="{{ $item->grup_instrumen_id }}">
                                                    <input type="hidden" name="kurikulum_instrumen_id"
                                                        value="{{ $item->kurikulum_instrumen_id }}">
                                                    <input type="hidden" name="jadwal_ami_id"
                                                        value="{{ $item->jadwal_ami_id }}">
                                                    <input type="hidden" name="sub_grup_id"
                                                        value="{{ $item->sub_grup_id }}">
                                                    <input type="hidden" name="jenis_instrumen"
                                                        value="{{ $jadwal->jenis_instrumen }}">
                                                    <input type="hidden" name="sub_butir_instrumen_id"
                                                        value="{{ $subss->id }}">
                                                    <input type="number" name="skor_persen3"
                                                        value="{{ @$jwb->skor_persen3 }}" class="form-control"
                                                        placeholder="Nilai 3 (%)" disabled>
                                                    {{-- <button class="btn btn-primary btn-block"
                                                        style="border-radius: 10px !important; margin-top:5px">Submit</button> --}}
                                                </form>
                                                Rata-rata
                                                <input type="text" disabled name=""
                                                    value="{{ @$jwb->skor_persen }} " class="form-control">

                                                Skor (Skala 1 - 4)
                                                <input type="text" disabled name=""
                                                    value="{{ @$jwb->skor }} " class="form-control">
                                                <hr>
                                                <hr>
                                            @else
                                                @if ($subss->upload_file == '1')
                                                    <a href="{{ url('file_subbutir_instrumen/' . $subss->id . '/' . $jadwal->id) }}"
                                                        title="Upload File">Upload File</a>
                                                    <form action="">
                                                        <input type="number" value="{{ $item->skor_persen1 }}"
                                                            class="form-control" placeholder="Nilai 1 (%)">

                                                    </form>
                                                    <hr>
                                                    <form action="">
                                                        <input type="number" value="{{ $item->skor_persen2 }}"
                                                            class="form-control" placeholder="Nilai 2 (%)">

                                                    </form>
                                                    <hr>
                                                    <form action="">
                                                        <input type="number" value="{{ $item->skor_persen3 }}"
                                                            class="form-control" placeholder="Nilai 3 (%)">

                                                    </form>
                                                    Rata-rata
                                                    <input type="text" disabled name=""
                                                        value="{{ @$jwb->skor_persen }} " class="form-control">

                                                    Skor (Skala 1 - 4)
                                                    <input type="text" disabled name=""
                                                        value="{{ @$jwb->skor }} " class="form-control">
                                                    <hr>
                                                    <hr>
                                                @else
                                                @endif
                                            @endif
                                        @endif

                                    </li>
                                @endforeach
                            </ul>

                        </td>
                        <form action="{{ url('store-penilaian_ami') }}" method="POST">
                            @csrf
                            <td>
                                <input type="hidden" name="butir_instrumen_id"
                                    value="{{ $item->butir_instrumen_id }}">
                                <input type="hidden" name="grup_instrumen_id"
                                    value="{{ $item->grup_instrumen_id }}">
                                <input type="hidden" name="kurikulum_instrumen_id"
                                    value="{{ $item->kurikulum_instrumen_id }}">
                                <input type="hidden" name="jadwal_ami_id" value="{{ $item->jadwal_ami_id }}">
                                <input type="hidden" name="sub_grup_id" value="{{ $item->sub_grup_id }}">
                                <input type="hidden" name="jenis_instrumen" value="{{ $jadwal->jenis_instrumen }}">
                                @php
                                    $persen = $item->skor_persen;
                                    if ($persen == 100) {
                                        $nilai = 4;
                                    } elseif ($persen >= 66.0 && $persen <= 99.0) {
                                        $nilai = 3;
                                    } elseif ($persen >= 33.0 && $persen <= 65.0) {
                                        $nilai = 2;
                                    } elseif ($persen >= 0.0 && $persen <= 35.0) {
                                        $nilai = 1;
                                    } else {
                                        $nilai = 0;
                                    }
                                    $getAVG = DB::table('jawabans')
                                        ->select(DB::raw('AVG(skor_persen) as sp, AVG(skor) as ss'))
                                        ->where('butir_instrumen_id', $item->butir_instrumen_id)
                                        ->where('jadwal_ami_id', $item->jadwal_ami_id)
                                        ->first();

                                        
                                        
                                @endphp
                                
                                <input name="skor_persen" type="number" value="{{ $getAVG->sp }}" disabled
                                    class="form-control">

                                    {{-- <input name="skor_persen" type="text" value="NININI" disabled
                                    class="form-control"> --}}
                            </td>
                            <td>
                                <input name="skor" type="number" value="{{ $getAVG->ss }}" disabled
                                    class="form-control" readonly>

                            </td>

                        </form>
                    </tr>
                    @php
                        $namagrupprev = $item->nama_grup_instrumen;
                        $namasubgrupprev = $item->nama_sub_grup;
                    @endphp
                @else
                    {{-- dede --}}
                    <tr>
                        <td>{{ $item->kode_instrumen }}</td>
                        <td>{{ $item->nama_instrumen }}</td>
                        <td>
                            @php
                                $subs = DB::table('sub_butir_instrumens')
                                    ->where('butir_instrumen_id', $item->butir_instrumen_id)
                                    ->get();

                            @endphp
                            <ul>
                                @foreach ($subs as $subss)
                                    @php
                                        $jwab = DB::table('jawabans')
                                            ->where('jadwal_ami_id', $item->jadwal_ami_id)
                                            ->where('sub_butir_instrumen_id', $subss->id)
                                            ->first();
                                    @endphp
                                    <li>{{ $subss->nama_sub_butir }}
                                        @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Auditor')
                                            @if ($subss->upload_file == '1')
                                                @if ($jadwal->tgl_akhir_upload < $today)
                                                    <a class="text-danger"
                                                        href="{{ url('file_subbutir_instrumen/' . $subss->id . '/' . $jadwal->id) }}"
                                                        title="Cek File">Batas Waktu
                                                        Upload telah
                                                        Selesai</a> <br>
                                                @else
                                                    <a href="{{ url('file_subbutir_instrumen/' . $subss->id . '/' . $jadwal->id) }}"
                                                        title="Cek File">Cek File</a> <br>
                                                @endif

                                                <form action="{{ url('store-penilaian_ami1') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="butir_instrumen_id"
                                                        value="{{ $item->butir_instrumen_id }}">
                                                    <input type="hidden" name="grup_instrumen_id"
                                                        value="{{ $item->grup_instrumen_id }}">
                                                    <input type="hidden" name="kurikulum_instrumen_id"
                                                        value="{{ $item->kurikulum_instrumen_id }}">
                                                    <input type="hidden" name="jadwal_ami_id"
                                                        value="{{ $item->jadwal_ami_id }}">
                                                    <input type="hidden" name="sub_grup_id"
                                                        value="{{ $item->sub_grup_id }}">
                                                    <input type="hidden" name="jenis_instrumen"
                                                        value="{{ $jadwal->jenis_instrumen }}">
                                                    <input type="hidden" name="sub_butir_instrumen_id"
                                                        value="{{ $subss->id }}">
                                                    <input type="number" name="skor_persen1"
                                                        value="{{ @$jwab->skor_persen1 }}" class="form-control"
                                                        placeholder="Nilai 1 (%)">
                                                    @if (Auth::user()->role == 'Admin')
                                                        <button class="btn btn-primary btn-block"
                                                            style="border-radius: 10px !important; margin-top:5px">Submit</button>
                                                    @elseif(Auth::user()->role == 'Auditor')
                                                        <button class="btn btn-primary btn-block"
                                                            style="border-radius: 10px !important; margin-top:5px"
                                                            {{ @$auditor1->auditor_satu != @$user->id ? 'disabled' : '' }}>Submit</button>
                                                    @endif
                                                </form>
                                                <hr>
                                                <form action="{{ url('store-penilaian_ami2') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="butir_instrumen_id"
                                                        value="{{ $item->butir_instrumen_id }}">
                                                    <input type="hidden" name="grup_instrumen_id"
                                                        value="{{ $item->grup_instrumen_id }}">
                                                    <input type="hidden" name="kurikulum_instrumen_id"
                                                        value="{{ $item->kurikulum_instrumen_id }}">
                                                    <input type="hidden" name="jadwal_ami_id"
                                                        value="{{ $item->jadwal_ami_id }}">
                                                    <input type="hidden" name="sub_grup_id"
                                                        value="{{ $item->sub_grup_id }}">
                                                    <input type="hidden" name="jenis_instrumen"
                                                        value="{{ $jadwal->jenis_instrumen }}">
                                                    <input type="hidden" name="sub_butir_instrumen_id"
                                                        value="{{ $subss->id }}">
                                                    <input type="number" name="skor_persen2"
                                                        value="{{ @$jwab->skor_persen2 }}" class="form-control"
                                                        placeholder="Nilai 2 (%)">
                                                    @if (Auth::user()->role == 'Admin')
                                                        <button class="btn btn-primary btn-block"
                                                            style="border-radius: 10px !important; margin-top:5px">Submit</button>
                                                    @elseif(Auth::user()->role == 'Auditor')
                                                        <button class="btn btn-primary btn-block"
                                                            style="border-radius: 10px !important; margin-top:5px"
                                                            {{ @$auditor2->auditor_dua != @$user->id ? 'disabled' : '' }}>Submit</button>
                                                    @endif
                                                </form>
                                                <hr>
                                                <form action="{{ url('store-penilaian_ami3') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="butir_instrumen_id"
                                                        value="{{ $item->butir_instrumen_id }}">
                                                    <input type="hidden" name="grup_instrumen_id"
                                                        value="{{ $item->grup_instrumen_id }}">
                                                    <input type="hidden" name="kurikulum_instrumen_id"
                                                        value="{{ $item->kurikulum_instrumen_id }}">
                                                    <input type="hidden" name="jadwal_ami_id"
                                                        value="{{ $item->jadwal_ami_id }}">
                                                    <input type="hidden" name="sub_grup_id"
                                                        value="{{ $item->sub_grup_id }}">
                                                    <input type="hidden" name="jenis_instrumen"
                                                        value="{{ $jadwal->jenis_instrumen }}">
                                                    <input type="hidden" name="sub_butir_instrumen_id"
                                                        value="{{ $subss->id }}">
                                                    <input type="number" name="skor_persen3"
                                                        value="{{ @$jwab->skor_persen3 }}" class="form-control"
                                                        placeholder="Nilai 3 (%)">

                                                    @if (@!$jwb->skor_persen2 || @!$jwb->skor_persen1)
                                                        @if (Auth::user()->role == 'Admin')
                                                            <button class="btn btn-primary btn-block" disabled
                                                                style="border-radius: 10px !important; margin-top:5px">Submit</button>
                                                        @elseif(Auth::user()->role == 'Auditor')
                                                            <button class="btn btn-primary btn-block" disabled
                                                                style="border-radius: 10px !important; margin-top:5px">Submit</button>
                                                        @endif
                                                    @else
                                                        @if (Auth::user()->role == 'Admin')
                                                            <button class="btn btn-primary btn-block"
                                                                style="border-radius: 10px !important; margin-top:5px">Submit</button>
                                                        @elseif(Auth::user()->role == 'Auditor')
                                                            <button class="btn btn-primary btn-block"
                                                                style="border-radius: 10px !important; margin-top:5px"
                                                                {{ @$auditor3->auditor_tiga != @$user->id ? 'disabled' : '' }}>Submit</button>
                                                        @endif
                                                    @endif


                                                </form>
                                                @php
                                                    $getAngka = DB::table('jawabans')
                                                        ->where('sub_butir_instrumen_id', $subss->id)
                                                        ->where('jadwal_ami_id', $item->jadwal_ami_id)
                                                        ->first();
                                                @endphp
                                                Rata-rata
                                                <input type="text" disabled name=""
                                                    value="{{ @$getAngka->skor_persen }} " class="form-control">

                                                Skor (Skala 1 - 4)
                                                <input type="text" disabled name=""
                                                    value="{{ @$getAngka->skor }} " class="form-control">
                                                <hr>
                                            @else
                                            @endif
                                        @else
                                        {{-- AUDITEE --}}
                                            @php

                                                $getAngkaa = DB::table('jawabans')
                                                    ->where('sub_butir_instrumen_id', $subss->id)
                                                    ->where('jadwal_ami_id', $item->jadwal_ami_id)
                                                    ->first();

                                                $jwabb = DB::table('jawabans')
                                                    ->where('jadwal_ami_id', $item->jadwal_ami_id)
                                                    ->where('sub_butir_instrumen_id', $subss->id)
                                                    ->first();
                                            @endphp
                                            @if ($jadwal->tgl_akhir_upload < $today)
                                                <a href="{{ url('file_subbutir_instrumen/' . $subss->id . '/' . $jadwal->id) }}"
                                                    title="Upload File" class="text-danger">Batas Waktuz
                                                    Upload telah
                                                    Selesai</a>
                                                <form action="">
                                                    <input type="number" value="{{ @$jwabb->skor_persen1 }}"
                                                        class="form-control" disabled placeholder="Nilai 1 (%)">
                                                </form>
                                                <hr>
                                                <form action="">
                                                    <input type="number" value="{{ @$jwabb->skor_persen2 }}"
                                                        class="form-control" disabled placeholder="Nilai 2 (%)">
                                                </form>
                                                <hr>
                                                <form action="">
                                                    <input type="number" value="{{ @$jwabb->skor_persen3 }}"
                                                        class="form-control" disabled placeholder="Nilai 3 (%)">
                                                </form>
                                                <hr>
                                                @php
                                                    $getAngka = DB::table('jawabans')
                                                        ->where('sub_butir_instrumen_id', $subss->id)
                                                        ->where('jadwal_ami_id', $item->jadwal_ami_id)
                                                        ->first();
                                                @endphp
                                                Rata-rata
                                                <input type="text" disabled name=""
                                                    value="{{ @$getAngka->skor_persen }} " class="form-control">

                                                Skor (Skala 1 - 4)
                                                <input type="text" disabled name=""
                                                    value="{{ @$getAngka->skor }}" class="form-control">
                                                <hr>
                                            @else
                                                @if ($subss->upload_file == '1')
                                                    <a href="{{ url('file_subbutir_instrumen/' . $subss->id . '/' . $jadwal->id) }}"
                                                        title="Upload File">Upload File</a>
                                                    <form action="">
                                                        <input type="number" class="form-control"
                                                            placeholder="Nilai 1 (%)">
                                                    </form>
                                                    <hr>
                                                    <form action="">
                                                        <input type="number" class="form-control"
                                                            placeholder="Nilai 2 (%)">
                                                    </form>
                                                    <hr>
                                                    <form action="">
                                                        <input type="number" class="form-control"
                                                            placeholder="Nilai 3 (%)">
                                                    </form>
                                                    <hr>
                                                    @php
                                                    $getAngka = DB::table('jawabans')
                                                        ->where('sub_butir_instrumen_id', $subss->id)
                                                        ->where('jadwal_ami_id', $item->jadwal_ami_id)
                                                        ->first();
                                                @endphp
                                                    Rata-rata
                                                    <input type="text" disabled name=""
                                                        value="{{ @$getAngka->skor_persen }}" class="form-control">

                                                    Skor (Skala 1 - 4)
                                                    <input type="text" disabled name=""
                                                        value="{{ @$getAngka->skor }}" class="form-control">
                                                    <hr>
                                                @else
                                                @endif
                                            @endif
                                        @endif

                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <form action="{{ url('store-penilaian_ami') }}" method="POST">
                            @csrf
                            <td>
                                <input type="hidden" name="butir_instrumen_id"
                                    value="{{ $item->butir_instrumen_id }}">
                                <input type="hidden" name="grup_instrumen_id"
                                    value="{{ $item->grup_instrumen_id }}">
                                <input type="hidden" name="kurikulum_instrumen_id"
                                    value="{{ $item->kurikulum_instrumen_id }}">
                                <input type="hidden" name="jadwal_ami_id" value="{{ $item->jadwal_ami_id }}">
                                <input type="hidden" name="sub_grup_id" value="{{ $item->sub_grup_id }}">
                                <input type="hidden" name="jenis_instrumen"
                                    value="{{ $jadwal->jenis_instrumen }}">
                                @php
                                    $persen = $item->skor_persen;
                                    if ($persen == 100) {
                                        $nilai = 4;
                                    } elseif ($persen >= 66.0 && $persen <= 99.0) {
                                        $nilai = 3;
                                    } elseif ($persen >= 33.0 && $persen <= 65.0) {
                                        $nilai = 2;
                                    } elseif ($persen >= 0.0 && $persen <= 35.0) {
                                        $nilai = 1;
                                    } else {
                                        $nilai = 0;
                                    }
                                    $getAVGS = DB::table('jawabans')
                                        ->select(DB::raw('AVG(skor_persen) as sp, AVG(skor) as ss'))
                                        ->where('butir_instrumen_id', $item->butir_instrumen_id)
                                        ->where('jadwal_ami_id', $item->jadwal_ami_id)
                                        ->first();
                                @endphp
                                <input name="skor_persen" type="number" value="{{ $getAVGS->sp }}" disabled
                                    class="form-control">
                                {{-- <input type="text" name="" value="NANANA" id=""> --}}
                            </td>
                            <td>
                                <input name="skor" type="number" value="{{ $getAVGS->ss }}" disabled
                                    class="form-control" readonly>
                            </td>

                        </form>
                    </tr>
                @endif

                @if ($k == $totalRows - 1)
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>

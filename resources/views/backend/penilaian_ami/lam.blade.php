<div class="table-responsive mt-4">
    <table id="myTable" class="table table-bordered" style="width: 100%;">
        <thead class="bg-primary text-white">
            <tr>
                <th></th>
                <th></th>
                <th>Pertanyaan</th>
                <th>Nilai</th>
                @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Auditor')
                    <th></th>
                @endif
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
                            colspan="{{ Auth::user()->role == 'Admin' || Auth::user()->role == 'Auditor' ? '5' : '4' }} ">
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
                        <form action="{{ url('store-penilaian_ami') }}" method="POST">
                            @csrf
                            <td>
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
                                {{-- <input name="skor" type="number" max="4" maxlength="4"
                                value="{{ $item->skor }}" class="form-control"> --}}
                                <select name="skor" id="" class="form-control" required>
                                    <option value=""
                                        {{ Auth::user()->role == 'Auditee' ? 'disabled' : '' }}>
                                        --Pilih--</option>
                                    <option value="0" {{ $item->skor == 0 ? 'selected' : '' }}
                                        {{ Auth::user()->role == 'Auditee' ? 'disabled' : '' }}>0
                                    </option>
                                    <option value="1" {{ $item->skor == 1 ? 'selected' : '' }}
                                        {{ Auth::user()->role == 'Auditee' ? 'disabled' : '' }}>1
                                    </option>
                                    <option value="2" {{ $item->skor == 2 ? 'selected' : '' }}
                                        {{ Auth::user()->role == 'Auditee' ? 'disabled' : '' }}>2
                                    </option>
                                    <option value="3" {{ $item->skor == 3 ? 'selected' : '' }}
                                        {{ Auth::user()->role == 'Auditee' ? 'disabled' : '' }}>3
                                    </option>
                                    <option value="4" {{ $item->skor == 4 ? 'selected' : '' }}
                                        {{ Auth::user()->role == 'Auditee' ? 'disabled' : '' }}>4
                                    </option>
                                </select>
                            </td>
                            @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Auditor')
                                <td>
                                    <button style="border-radius: 10px !important;"
                                        class="btn btn-sm btn-primary">Submit</button>
                                </td>
                            @endif
                        </form>
                    </tr>
                    @php
                        $namagrupprev = $item->nama_grup_instrumen;
                        $namasubgrupprev = $item->nama_sub_grup;
                    @endphp
                @else
                    <tr>
                        <td>{{ $item->kode_instrumen }}</td>
                        <td>{{ $item->nama_instrumen }}</td>
                        <form action="{{ url('store-penilaian_ami') }}" method="POST">
                            @csrf
                            <td>
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
                                {{-- <input name="skor" type="number" max="4" maxlength="4"
                                value="{{ $item->skor }}" class="form-control"> --}}
                                <select name="skor" id="" class="form-control" required>
                                    <option value=""
                                        {{ Auth::user()->role == 'Auditee' ? 'disabled' : '' }}>
                                        --Pilih--</option>
                                    <option value="0" {{ $item->skor == 0 ? 'selected' : '' }}
                                        {{ Auth::user()->role == 'Auditee' ? 'disabled' : '' }}>0
                                    </option>
                                    <option value="1" {{ $item->skor == 1 ? 'selected' : '' }}
                                        {{ Auth::user()->role == 'Auditee' ? 'disabled' : '' }}>1
                                    </option>
                                    <option value="2" {{ $item->skor == 2 ? 'selected' : '' }}
                                        {{ Auth::user()->role == 'Auditee' ? 'disabled' : '' }}>2
                                    </option>
                                    <option value="3" {{ $item->skor == 3 ? 'selected' : '' }}
                                        {{ Auth::user()->role == 'Auditee' ? 'disabled' : '' }}>3
                                    </option>
                                    <option value="4" {{ $item->skor == 4 ? 'selected' : '' }}
                                        {{ Auth::user()->role == 'Auditee' ? 'disabled' : '' }}>4
                                    </option>
                                </select>
                            </td>
                            @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Auditor')
                                <td>
                                    <button style="border-radius: 10px !important;"
                                        class="btn btn-sm btn-primary">Submit</button>
                                </td>
                            @endif
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
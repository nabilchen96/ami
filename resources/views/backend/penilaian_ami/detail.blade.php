@extends('backend.app')
@push('style')
    <style>
        #myTable_filter input {
            height: 29.67px !important;
        }

        #myTable_length select {
            height: 29.67px !important;
        }

        .btn {
            border-radius: 50px !important;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #9e9e9e21 !important;
        }
    </style>
@endpush
@section('content')
    <div class="row" style="margin-top: -200px;">
        <div class="col-md-12">
            <div class="row">
                <div class="col-12 col-xl-8 mb-xl-0">
                    <h3 class="font-weight-bold">
                        <a href="{{ url('penilaian_ami') }}">
                            <i class="bi bi-arrow-left text-dark"></i>
                        </a>
                        Detail Penilaian AMI
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card w-100">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="mb-1 table table-striped">
                            <tr>
                                <td width="25%">Judul Audit</td>
                                <td>:</td>
                                <td width="25%">{{ $jadwal->judul }}</td>
                                <td width="25%">Periode Audit</td>
                                <td>:</td>
                                <td width="25%">{{ $jadwal->tgl_awal_upload }} s/d
                                    {{ $jadwal->tgl_akhir_upload }}
                                </td>
                            </tr>
                            <tr>
                                <td>Kurikulum</td>
                                <td>:</td>
                                <td>
                                    {{ $jadwal->nama_kurikulum }}
                                </td>
                                <td>Auditee</td>
                                <td>:</td>
                                <td>{{ $jadwal->prodi }}</td>
                            </tr>
                        </table>
                    </div>
                    <div id="spider-chart-container" class="border" style="padding-top: 20px; width: 100%; margin: 0 auto">
                    </div>
                    <div class="table-responsive mt-4">
                        <table id="myTable" class="table table-bordered table-striped" style="width: 100%;">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Pertanyaan</th>
                                    <th>Nilai</th>
                                    <th></th>
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
                                            <td colspan="5"><b>{{ $item->nama_grup_instrumen }}</b></td>
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
                                                    <input name="skor" type="number" max="4" maxlength="4"
                                                        value="{{ $item->skor }}" class="form-control">
                                                </td>
                                                <td>
                                                    <button style="border-radius: 10px !important;"
                                                        class="btn btn-sm btn-primary">Submit</button>
                                                </td>
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
                                                    <input name="skor" type="number" max="4" maxlength="4"
                                                        value="{{ $item->skor }}" class="form-control">
                                                </td>
                                                <td>
                                                    <button style="border-radius: 10px !important;"
                                                        class="btn btn-sm btn-primary">Submit</button>
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
                    {{-- <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-home-tab" data-toggle="tab" data-target="#nav-home"
                                type="button" role="tab" aria-controls="nav-home" aria-selected="true">Data</button>
                            <button class="nav-link" id="nav-profile-tab" data-toggle="tab" data-target="#nav-profile"
                                type="button" role="tab" aria-controls="nav-profile"
                                aria-selected="false">Chart</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                            aria-labelledby="nav-home-tab">

                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">

                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    {{-- <span style="
    width: 200px !important;
    white-space: normal;
    display: inline-block !important;
    "></span> --}}
@endsection
@push('script')
    <script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            getData()
        })

        // Mengambil URL saat ini
        var url = window.location.href;

        // Membagi URL menjadi array menggunakan '/' sebagai pemisah
        var urlParts = url.split('/');

        // Mengambil elemen terakhir dari array yang merupakan angka
        var angka = urlParts[urlParts.length - 1];

        axios.get('/data-penilaian_ami/' + angka).then(function(res) {

            // let nama = res.data.map(function(e) {
            //     return e.nama_instrumen
            // })

            let nama = res.data.map(function(e, index) {
                return index + 1;
            });


            let skor = res.data.map(function(e) {
                return e.skor;
            });

            nilaiami(nama, skor)
        })

        function nilaiami(nama, skor) {
            Highcharts.chart('spider-chart-container', {
                chart: {
                    polar: true,
                    type: 'line'
                },
                title: {
                    text: 'Spider Chart'
                },
                xAxis: {
                    categories: nama,
                    tickmarkPlacement: 'on',
                    lineWidth: 0
                },
                yAxis: {
                    gridLineInterpolation: 'polygon',
                    lineWidth: 0,
                    min: 0
                },
                series: [{
                    name: 'Nilai',
                    data: skor,
                    pointPlacement: 'on'
                }]
            });


        }
    </script>
@endpush

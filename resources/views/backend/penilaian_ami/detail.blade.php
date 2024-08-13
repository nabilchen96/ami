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
                                <td>Kurikulum / Jenis Instrumen</td>
                                <td>:</td>
                                <td>
                                    {{ $jadwal->nama_kurikulum }} / {{ $jadwal->jenis_instrumen }}
                                </td>
                                <td>Auditee</td>
                                <td>:</td>
                                <td>{{ $jadwal->prodi }}</td>
                            </tr>
                            <tr>
                                <td>List Temuan</td>
                                <td>:</td>
                                <td colspan="3">
                                    {{-- <a target="_blank"
                                        href="{{ $jadwal->link_upload_dokumen }}">{{ $jadwal->link_upload_dokumen }}</a> --}}
                                    <a href="{{ url('record_temuan/' . $jadwal->id) }}" class="btn btn-warning">List
                                        Temuan</a>
                                </td>
                                <td>
                                    {{-- <a href="{{ url('record_temuan/' . $jadwal->id) }}" class="btn btn-warning">List
                                        Temuan</a> --}}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="spider-chart-container" class="border" style="padding-top: 20px; width: 100%; margin: 0 auto">
                    </div>

                    @if ($jadwal->jenis_instrumen == 'BAN-PT' || $jadwal->jenis_instrumen == 'LAM')
                        @include('backend.penilaian_ami.lam')
                    @elseif($jadwal->jenis_instrumen == 'SN-DIKTI')
                        @include('backend.penilaian_ami.sndikti')
                    @endif

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


    <script src="{{ asset('js/accessibility.js') }}"></script>
    <script src="{{ asset('js/highcharts.js') }}"></script>
    <script src="{{ asset('js/exporting.js') }}"></script>
    <script src="{{ asset('js/highcharts-more.js') }}"></script>

    {{-- <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script> --}}
    <script>
        // document.addEventListener('DOMContentLoaded', function() {
        //     getData()
        // })

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

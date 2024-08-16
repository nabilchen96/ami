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
                    <h3 class="font-weight-bold">Detail Laporan AMI</h3>
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
                            <tr>
                                <td>Rata-rata</td>
                                <td>:</td>
                                <td colspan="4"><b>{{ round($rata2,2) }}</b></td>
                            </tr>
                        </table>
                    </div>

                    <div id="spider-chart-container" class="border" style="padding-top: 20px; width: 100%; height: 300px; margin: 0 auto">
                    </div>
                    <div class="table-responsive mt-4">
                        <table id="myTable" class="table table-bordered table-striped" style="width: 100%;">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th></th>
                                    <th>Nama Instrumen</th>
                                    <th>Nilai</th>
                                    {{-- <th></th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $k => $item)
                                    <tr>
                                        <td>{{ $k + 1 }}</td>
                                        <td>{{ $item->kode_instrumen }}. {{ $item->nama_instrumen }}</td>
                                        <td>{{ $item->skor }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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

    <script>
        // document.addEventListener('DOMContentLoaded', function() {
        //     getData()
        // })

        // function getData() {
        //     $("#myTable").DataTable({
        //         "ordering": false,
        //     })
        // }
    </script>
    
    {{-- <script src="{{ asset('js/highcharts.js') }}"></script>
    <script src="{{ asset('js/exporting.js') }}"></script>
    <script src="{{ asset('js/accessibility.js') }}"></script>
    <script src="{{ asset('js/highcharts-more.js') }}"></script> --}}

    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script>
        // Mengambil URL saat ini
        var url = window.location.href;

        // Membagi URL menjadi array menggunakan '/' sebagai pemisah
        var urlParts = url.split('/');

        // Mengambil elemen terakhir dari array yang merupakan angka
        var angka = urlParts[urlParts.length - 2];
        var angka2 = urlParts[urlParts.length - 1];

        // console.log(angka2);

        axios.get('/data-laporan_ami/' + angka + '/' + angka2).then(function(res) {

            let grup = res.data.map(function(e, index) {
                // return e.nama_grup_instrumen
                return index + 1
            })

            let nilai = res.data.map(function(e) {
                return e.skor;
            });

            nilaiami(grup, nilai)
        })

        function nilaiami(grup, nilai) {
            Highcharts.chart('spider-chart-container', {
                chart: {
                    polar: true,
                    type: 'line'
                },
                title: {
                    text: ''
                },
                xAxis: {
                    categories: grup,
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
                    data: nilai,
                    pointPlacement: 'on'
                }]
            });


        }
    </script>
@endpush

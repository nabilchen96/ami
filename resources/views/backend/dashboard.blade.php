@extends('backend.app')
@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css">
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

        th,
        td {
            white-space: nowrap !important;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: center;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .biru {
            background-color: blue;
            color: white;
        }

        .hijau {
            background-color: green;
            color: white;
        }

        .kuning {
            background-color: yellow;
        }

        .oren {
            background-color: orange;
        }

        .merah {
            background-color: red;
            color: white;
        }

        .rotate-text {
            white-space: nowrap;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
        }
    </style>
@endpush
@section('content')
    @php
        @@$data_user = Auth::user();
    @endphp

    <div class="row" style="margin-top: -200px;">
        <div class="col-md-12 grid-margin">
            <div class="row mt-4" style="background-color: #e6d6d6; opacity: 0.6; border-radius:10px; padding: 12px;">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold" >Dashboard</h3>
                    <h6 class="font-weight-bold mb-0" >Hi, {{ Auth::user()->name }}.
                        Welcome back to SIstem Informasi AMI</h6>
                </div>
            </div>
            <div class="row mt-4">
                
            </div>
        </div>
    </div>
    @if (Auth::user()->role == "Auditor" || Auth::user()->role = "Auditee")
    <div class="row">
        <div class="mt-4 p-5  text-black rounded" style="width: 100% !important; background-color:#f2f2f2 ">
            <h4>Berikut merupakan standar penilaian AMI SN-Dikti bagi auditor :</h4>
            <table class="table">
                <tr>
                    <th>RENTANG NILAI</th>
                    <th>NILAI</th>
                    <th>KRITERIA</th>
                    <th>INDIKATOR</th>
                </tr>
                <tr>
                    <td>100 %</td>
                    <td>4</td>
                    <td>Terpenuhi</td>
                    <td>Tersedia dokumen dengan lengkap dan sesuai, dilengkapi nama jelas dan tanda tangan penanggung jawab</td>
                </tr>
                <tr>
                    <td>66 % - 99%</td>
                    <td>3</td>
                    <td>Cukup Terpenuhi</td>
                    <td>Tersedia dokumen yang sesuai namun belum lengkap seperti nama jelas, tanda tangan penanggung jawab</td>
                </tr>
                <tr>
                    <td>33 % - 65%</td>
                    <td>2</td>
                    <td>Kurang Terpenuhi</td>
                    <td>Tersedia dokumen yang diupload namun belum sesuai dengan sasaran standar yang diminta</td>
                </tr>
                <tr>
                    <td>0 – 32%</td>
                    <td>1</td>
                    <td>Tidak Terpenuhi</td>
                    <td>Tidak ada dokumen yang diupload</td>
                </tr>
            </table>
            <br>
            <p>Panduan SiAMI dapat diunduh pada link berikut : …………….</p>
          </div>
    </div>
    @endif
    
@endsection
@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            getData()
        })

        function getData() {
            @$("#myTable").DataTable({
                "ordering": false,
            })
        }
    </script>
@endpush

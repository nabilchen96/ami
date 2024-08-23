@extends('backend.app')
@push('style')
    <style>
        .table-header {
            vertical-align: middle;
            font-size: 12px;
        }
    </style>
    <script src="https://cdn.ckeditor.com/4.24.0-lts/standard/ckeditor.js"></script>
@endpush
@section('content')
    <div class="row" style="margin-top: -200px;">
        <div class="col-md-12">
            <div class="row">
                <div class="col-12 col-xl-8 mb-xl-0">
                    <h3 class="font-weight-bold" style="color: #ffffff">Berita Acara</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card w-100">
                <div class="card-body">
                    <div class="container mt-4">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>{{ $message }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @elseif($message = Session::get('failed'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong> {{ $message }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form method="post"
                            action="@if (Auth::user()->role == 'Auditor' || Auth::user()->role == 'Auditee'){{ url('update_ba') }} @elseif(Auth::user()->role == 'Admin') {{ url('update_ba_admin') }}@endif" enctype="multipart/form-data">

                            @csrf
                            <table class="table table-borderless table-header">
                                <tr>
                                    <td colspan="2" class="text-center">
                                        <h4>BERITA ACARA AUDIT MUTU INTERNAL 
                                            @if (@$dataBA->ttd_auditor && @$dataBA->ttd_auditee)
                                            <a href="{{ url('ba_cetak/'.@$jadwal->id) }}" target="_blank" class="btn btn-primary">Cetak</a> 
                                            @endif
                                        </h4>

                                        <br>
                                        <input type="text" class="form-control" placeholder="Nomor Surat, Diisi oleh SPM"
                                            name="nomor_surat" value="{{ @$dataBA->nomor_surat }}" id=""
                                            {{ Auth::user()->role == 'Auditor' || Auth::user()->role == 'Auditee' ? 'disabled' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <input type="hidden" name="jadwal_ami_id" value="{{ $jadwal->id }}" id="">
                                    <td colspan="2">
                                        Dengan ini dinyatakan bahwa pada tanggal
                                        {{ date('d', strtotime($jadwal->tgl_awal_upload)) }} -
                                        {{ date('d', strtotime($jadwal->tgl_akhir_upload)) }}
                                        {{ date('M', strtotime($jadwal->tgl_awal_upload)) }}
                                        {{ date('Y', strtotime($jadwal->tgl_awal_upload)) }}, Auditor:
                                    </td>
                                </tr>
                                <tr>
                                    <td>Nama</td>
                                    <input type="hidden" name="lead_auditor" value="{{ Auth::user()->id }}"
                                        id="">
                                    <td>: {{ Auth::user()->name }}</td>
                                </tr>
                                <tr>
                                    <td>NIP/NIDN</td>
                                    <td>: {{ Auth::user()->nip }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Telah melaksanakan audit mutu internal (AMI):
                                    </td>
                                </tr>
                                <tr>
                                    <td>Nama Ka. Prodi/Perwakilan</td>
                                    <td>: <input type="text" name="nama_auditee" value="{{ @$dataBA->nama_auditee }}"
                                            style="width: 300px"
                                            {{ Auth::user()->role == 'Admin' || Auth::user()->role == 'Auditee' ? 'disabled' : '' }}
                                            required> </td>
                                </tr>
                                <tr>
                                    <td>NIP/NIDN</td>
                                    <td>: <input type="text" name="nip_auditee" value="{{ @$dataBA->nip_auditee }}"
                                            style="width: 300px"
                                            {{ Auth::user()->role == 'Admin' || Auth::user()->role == 'Auditee' ? 'disabled' : '' }}
                                            required></td>
                                </tr>
                                <tr>
                                    <td>Program Studi</td>
                                    <td>: {{ $jadwal->prodi }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Catatan Pelaksanaan Audit
                                        <textarea {{ Auth::user()->role == 'Admin' || Auth::user()->role == 'Auditee' ? 'disabled' : '' }} name="isi_ba"
                                            id="isi_ba" cols="30" rows="10">{{ @$dataBA->isi_ba }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Publish?
                                        <select name="is_publish" id="is_publish" class="form-control"
                                            {{ Auth::user()->role == 'Admin' || Auth::user()->role == 'Auditee' ? 'disabled' : '' }}>
                                            <option value="1">Ya</option>
                                            <option value="0">Tidak</option>
                                        </select>
                                    </td>
                                </tr>
                                @if (Auth::user()->role != "Admin")
                                <tr>
                                    <td colspan="2">
                                        Upload Tanda Tangan
                                        @if (Auth::user()->role == "Auditor")
                                        <input type="file" name="ttd_auditor" class="form-control" style="height: 45px;" {{ Auth::user()->role == "Auditor" ? 'required' : '' }} >
                                        <p class="text-danger">Ukuran Max: 2Mb, Format : JPG, PNG, JPEG</p>
                                        @else
                                        <input type="file" name="ttd_auditee" class="form-control" style="height: 45px;" {{ Auth::user()->role == "Auditor" ? 'required' : '' }} >
                                        <p class="text-danger">Ukuran Max: 2Mb, Format : JPG, PNG, JPEG</p>
                                        @endif
                                        
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="text-center">Tanda Tangan Auditor <br> @if (@$dataBA->ttd_auditor)
                                        <img src="{{ asset('storage/' . @$dataBA->ttd_auditor) }}" alt="" style="width: 300px; height:300px;"> </td>
                                    @else
                                        <img src="https://png.pngtree.com/png-vector/20190820/ourmid/pngtree-no-image-vector-illustration-isolated-png-image_1694547.jpg" alt="" style="width: 300px; height:300px;"> </td>
                                    @endif 
                                    <td class="text-center">Tanda Tangan Auditee <br> @if (@$dataBA->ttd_auditee)
                                        <img src="{{ asset('storage/' . @$dataBA->ttd_auditee) }}" alt="" style="width: 300px; height:300px;"> </td>
                                    @else
                                        <img src="https://png.pngtree.com/png-vector/20190820/ourmid/pngtree-no-image-vector-illustration-isolated-png-image_1694547.jpg" alt="" style="width: 300px; height:300px;"> </td>
                                    @endif </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <button type="submit" class="btn btn-primary btn-block"> Submit </button>
                                    </td>
                                </tr>
                                
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#isi_ba'), {
                minHeight: '500px',
                height: 700,
            })
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush

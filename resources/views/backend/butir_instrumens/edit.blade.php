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

        th,
        td {
            white-space: nowrap !important;
            vertical-align: middle !important;
        }
    </style>
@endpush
@section('content')
    <div class="row" style="margin-top: -200px;">
        <div class="col-md-12">
            <div class="row">
                <div class="col-12 col-xl-8 mb-xl-0">
                    <h3 class="font-weight-bold">Edit Butir Instrumen </h3>

                </div>
            </div>
        </div>
    </div>
    <div class="section-body">
        <div class="card">
            <div class="card-header">
                Ubah Data Instrumen
            </div>

            <div class="card-body">
                <a href="{{ url('butir_instrumen/' . $data->kurikulum_instrumen_id) }}" class="btn btn-success">Kembali</a>
                <hr>
                <form id="form" method="POST">

                    @csrf

                    <div id="wrapper-modal">
                        <div class="modal-body border-top">
                            <input type="hidden" name="id" id="id" value="{{ $data->id }}">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Kode Instrumen</label>
                                <input name="kode_instrumen" id="kode_instrumen" type="text" placeholder="Kode Instrumen"
                                    class="form-control form-control-sm" value="{{ $data->kode_instrumen }}" required>
                                <span class="text-danger error" style="font-size: 12px;" id="kode_instrumen_alert"></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Nama Instrumen</label>
                                <input name="nama_instrumen" id="nama_instrumen" type="text" placeholder="Nama Instrumen"
                                    class="form-control form-control-sm" value="{{ $data->nama_instrumen }}" required>
                                <span class="text-danger error" style="font-size: 12px;" id="nama_instrumen_alert"></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Grup Instrumen</label>
                                <select class="form-control" name="grup_instrumen_id" id="grup_instrumen_id">
                                    @foreach ($grup_instrumen as $gi)
                                        <option value="{{ $gi->id }}"
                                            {{ $data->grup_instrumen_id == $gi->id ? 'selected' : '' }}>
                                            {{ $gi->nama_grup_instrumen }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error" style="font-size: 12px;"
                                    id="grup_instrumen_id_alert"></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Sub Grup Instrumen</label>
                                <select class="form-control" name="sub_grup_id" id="sub_grup_id">
                                    <option value="">--Pilih--</option>
                                    @foreach ($sub_grup as $sg)
                                        <option value="{{ $sg->id }}"
                                            {{ $data->sub_grup_id == $sg->id ? 'selected' : '' }}>{{ $sg->nama_sub_grup }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger error" style="font-size: 12px;" id="sub_grup_id_alert"></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Keterangan</label>
                                <textarea class="form-control" name="keterangan" id="keterangan" cols="30" rows="10">{{ $data->keterangan }}</textarea>
                                <span class="text-danger error" style="font-size: 12px;" id="keterangan_alert"></span>
                            </div>
                        </div>
                    </div>
                    <button id="tombol_kirim" class="btn btn-primary btn-sm">Submit</button>
                    <a href="{{ url('butir_instrumen/' . $data->kurikulum_instrumen_id) }}"
                        class="btn btn-success">Kembali</a>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#keterangan'), {
                minHeight: '500px'
            })
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });
    </script>
    <script>
        $(document).ready(function() {
            $('#grup_instrumen_id').on('change', function() {
                let id = $(this).val();
                $('#sub_grup_id').empty();
                $('#sub_grup_id').append(`<option value="0" disabled selected>Processing...</option>`);
                $.ajax({
                    type: 'GET',
                    url: '/sub_grups_by_grup_id/' + id,
                    success: function(response) {
                        var response = JSON.parse(response);
                        console.log(response);
                        $('#sub_grup_id').empty();
                        $('#sub_grup_id').append(
                            `<option value="0" disabled selected>--Pilih Sub--</option>`);
                        response.forEach(element => {
                            $('#sub_grup_id').append(
                                `<option value="${element['id']}">${element['nama_sub_grup']} </option>`
                            );
                        });
                    }
                });
            });
        });
    </script>
    <script>
        form.onsubmit = (e) => {

            let formData = new FormData(form);

            e.preventDefault();

            document.getElementById("tombol_kirim").disabled = true;

            axios({
                    method: 'post',
                    url: formData.get('id') == '' ? '/store-butir_instrumen' : '/update-butir_instrumen',
                    data: formData,
                })
                .then(function(res) {
                    //handle success         
                    if (res.data.responCode == 1) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: res.data.respon,
                            timer: 3000,
                            showConfirmButton: false
                        })


                        setTimeout(() => {
                            location.reload(res.data.respon);
                        }, 1500);

                    } else {

                        console.log('terjadi error');
                    }

                    document.getElementById("tombol_kirim").disabled = false;
                })
                .catch(function(res) {
                    document.getElementById("tombol_kirim").disabled = false;
                    //handle error
                    console.log(res);
                });
        }
    </script>
@endpush

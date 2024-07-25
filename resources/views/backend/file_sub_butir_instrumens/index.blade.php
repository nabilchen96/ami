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

        /* th,
                                        td {
                                            white-space: nowrap !important;
                                            vertical-align: middle !important;
                                        } */
    </style>
@endpush
@section('content')
    <div class="row" style="margin-top: -200px;">
        <div class="col-md-12">
            <div class="row">
                <div class="col-12 col-xl-8 mb-xl-0">
                    <h3 class="font-weight-bold">Data File Berkas </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mt-4">
            <div class="card w-100">
                <div class="card-body">
                    {{ $subbutir->nama_sub_butir }}
                    <hr>
                    @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Auditee')
                        <button type="button" class="btn btn-primary btn-sm mb-4" data-toggle="modal" data-target="#modal">
                            Tambah
                        </button>
                    @endif

                    <div class="table-responsive">
                        <table id="myTable" class="table table-bordered table-striped" style="width: 100%;">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Name</th>
                                    <th>Berkas</th>
                                    <th>Tampilkan File?</th>
                                    <th>Waktu Upload</th>
                                    <th width="5%"></th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form" enctype="multipart/form-data">
                    <div class="modal-header p-3">
                        <h5 class="modal-title m-2" id="exampleModalLabel">Sub Butir Instrumen</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="sub_butir_instrumen_id" id="sub_butir_instrumen_id"
                            value="{{ $sub_butir_instrumen_id }}">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Nama File</label>
                            <input name="nama_file" id="nama_file" type="text" placeholder="Nama File"
                                class="form-control form-control-sm">
                            <span class="text-danger error" style="font-size: 12px;" id="nama_file_alert"></span>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Berkas</label>
                            <input name="file_upload" id="file_upload" type="file" placeholder="File Berkas"
                                class="form-control form-control-sm">
                            <span class="text-danger error" style="font-size: 12px;" id="file_upload_alert"></span>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Tampilkan File?</label>
                            <select class="form-control" name="tampilkan" id="tampilkan">
                                <option value="">--Pilih--</option>
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                            <span class="text-danger error" style="font-size: 12px;" id="tampilkan_alert"></span>
                        </div>

                    </div>
                    <div class="modal-footer p-3">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                        <button id="tombol_kirim" class="btn btn-primary btn-sm">Submit</button>
                    </div>
                </form>
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
        document.addEventListener('DOMContentLoaded', function() {
            getData()
        })

        function getData() {
            var sub_butir_instrumen_id = document.getElementById('sub_butir_instrumen_id').value
            $("#myTable").DataTable({
                "ordering": false,
                ajax: '/data-file_subbutir_instrumen/' + sub_butir_instrumen_id,
                processing: true,
                scrollX: true,
                scrollCollapse: true,
                'language': {
                    'loadingRecords': '&nbsp;',
                    'processing': 'Loading...'
                },
                columns: [{
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "nama_file"
                    },
                    {
                        render: function(data, type, row, meta) {
                            return `<a href="/storage/${row.file_upload}" target="_blank">Lihat File</a>`
                        }
                    },
                    {
                        render: function(data, type, row, meta) {

                            if (row.tampilkan == "1") {
                                return `<span class="badge badge-success">Ya</span>`
                            } else if (row.tampilkan == "0") {
                                return `<span class="badge badge-warning">Tidak</span>`
                            }
                        }
                    },

                    {
                        data: "created_at"
                    },

                    {
                        render: function(data, type, row, meta) {
                            return `<a data-toggle="modal" data-target="#modal"
                                    data-bs-id=` + (row.id) + ` href="javascript:void(0)">
                                    <i style="font-size: 1.5rem;" class="text-success bi bi-grid"></i>
                                </a>`
                        }
                    },
                    {
                        render: function(data, type, row, meta) {
                            return `<a href="javascript:void(0)" onclick="hapusData(` + (row
                                .id) + `)">
                                    <i style="font-size: 1.5rem;" class="text-danger bi bi-trash"></i>
                                </a>`
                        }
                    },
                ]
            })
        }

        $('#modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('bs-id') // Extract info from data-* attributes
            var cok = $("#myTable").DataTable().rows().data().toArray()

            let cokData = cok.filter((dt) => {
                return dt.id == recipient;
            })

            document.getElementById("form").reset();
            document.getElementById('id').value = ''
            $('.error').empty();

            if (recipient) {
                var modal = $(this)
                modal.find('#id').val(cokData[0].id)
                modal.find('#nama_file').val(cokData[0].nama_file)
                modal.find('#tampilkan').val(cokData[0].tampilkan)
            }
        })

        form.onsubmit = (e) => {

            let formData = new FormData(form);

            e.preventDefault();

            document.getElementById("tombol_kirim").disabled = true;

            axios({
                    method: 'post',
                    url: formData.get('id') == '' ? '/store-file_subbutir_instrumen' :
                        '/update-file_subbutir_instrumen',
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

                        $("#modal").modal("hide");
                        $('#myTable').DataTable().clear().destroy();
                        getData()

                    } else {
                        document.getElementById('file_upload_alert').innerHTML = res.data.respon.file_upload ?? ''
                        document.getElementById('nama_file_alert').innerHTML = res.data.respon.nama_file ?? ''
                        document.getElementById('tampilkan_alert').innerHTML = res.data.respon.tampilkan ?? ''
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


        hapusData = (id) => {
            Swal.fire({
                title: "Yakin hapus data?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonColor: '#3085d6',
                cancelButtonText: "Batal"

            }).then((result) => {

                if (result.value) {
                    axios.post('/delete-file_subbutir_instrumen', {
                            id
                        })
                        .then((response) => {
                            if (response.data.responCode == 1) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    timer: 2000,
                                    showConfirmButton: false
                                })

                                $('#myTable').DataTable().clear().destroy();
                                getData();

                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Gagal...',
                                    text: response.data.respon,
                                })
                            }
                        }, (error) => {
                            console.log(error);
                        });
                }

            });
        }
    </script>
@endpush

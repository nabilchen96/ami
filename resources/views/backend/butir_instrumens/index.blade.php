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
                    <h3 class="font-weight-bold">Data Butir Instrumen </h3>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card w-100">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-sm mb-4" data-toggle="modal" data-target="#modal">
                        Tambah
                    </button>
                    <div class="table-responsive">
                        <table id="myTable" class="table table-bordered table-striped" style="width: 100%;">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Kriteria / Sub Kriteria</th>
                                    <th>Kode</th>
                                    <th>Instrumen</th>
                                    <th>User</th>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="form">
                    <div class="modal-header p-3">
                        <h5 class="modal-title m-2" id="exampleModalLabel">User Form</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="kurikulum_id" name="kurikulum_id" value="{{ $kurikulum_id }}">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Kode Instrumen</label>
                            <input name="kode_instrumen" id="kode_instrumen" type="text" placeholder="Kode Instrumen"
                                class="form-control form-control-sm" required>
                            <span class="text-danger error" style="font-size: 12px;" id="kode_instrumen_alert"></span>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Nama Instrumen</label>
                            <input name="nama_instrumen" id="nama_instrumen" type="text" placeholder="Nama Instrumen"
                                class="form-control form-control-sm" required>
                            <span class="text-danger error" style="font-size: 12px;" id="nama_instrumen_alert"></span>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Grup Instrumen</label>
                            <select class="form-control" name="grup_instrumen_id" id="grup_instrumen_id">
                                @foreach ($grup_instrumen as $gi)
                                    <option value="{{ $gi->id }}">{{ $gi->nama_grup_instrumen }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error" style="font-size: 12px;" id="grup_instrumen_id_alert"></span>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Sub Grup Instrumen</label>
                            <select class="form-control" name="sub_grup_id" id="sub_grup_id">
                                @foreach ($sub_grup as $gi)
                                    <option value="{{ $gi->id }}">{{ $gi->nama_sub_grup }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error" style="font-size: 12px;" id="sub_grup_id_alert"></span>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Keterangan</label>
                           <textarea class="form-control" name="keterangan" id="keterangan" cols="30" rows="10"></textarea>
                           <span class="text-danger error" style="font-size: 12px;" id="keterangan_alert"></span>
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
    <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>
    <script>
        ClassicEditor
                .create( document.querySelector( '#keterangan' ),{
                    minHeight: '500px'
                } )
                .then( editor => {
                        console.log( editor );
                } )
                .catch( error => {
                        console.error( error );
                } );
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            getData()
        })

        function getData() {
            var kurikulum_id = document.getElementById('kurikulum_id').value
            $("#myTable").DataTable({
                "ordering": false,
                ajax: '/data-butir_instrumen/'+ kurikulum_id,
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
                        render: function(data, type, row, meta) {
                            return `
                                ${row.nama_grup_instrumen} / ${row.nama_sub_grup}
                            `
                        }
                    },

                    {
                        data: "kode_instrumen"
                    },
                    {
                        data: "nama_instrumen"
                    },
                    {
                        data: "name"
                    },

                    {
                        render: function(data, type, row, meta) {
                            return `<a href="edit-butir_instrumen/${row.id}">
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
                modal.find('#nama_butir_instrumen').val(cokData[0].nama_butir_instrumen)
            }
        })

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

                        $("#modal").modal("hide");
                        
                        $('#myTable').DataTable().clear().destroy();
                        getData()
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
                    axios.post('/delete-butir_instrumen', {
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

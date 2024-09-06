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
                    <h3 class="font-weight-bold" style="color: #ffffff">Data Jadwal AMI</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card w-100">
                <div class="card-body">
                    @if (Auth::user()->role == "Admin")
                    <button type="button" class="btn btn-primary btn-sm mb-4" data-toggle="modal" data-target="#modal">
                        Tambah
                    </button>
                    @endif
                    
                    <div class="table-responsive">
                        <table id="myTable" class="table table-bordered table-striped" style="width: 100%;">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Audit</th>
                                    <th>Instrumen AMI</th>
                                    <th>Auditor</th>
                                    <th>Auditee</th>
                                    <th>Periode Upload</th>
                                    <th>Periode Penilaian</th>
                                    <th>Aktif?</th>
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
                        <h5 class="modal-title m-2" id="exampleModalLabel">Jadwal Form</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Judul</label>
                            <input name="judul" id="judul" type="text" placeholder="Judul"
                                class="form-control form-control-sm" required>
                            <span class="text-danger error" style="font-size: 12px;" id="judul_alert"></span>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Periode</label>
                            <select name="priode" id="priode" class="form-control" required>
                                <option value="">--Pilih--</option>
                                <?php
                                    for($i=date('Y'); $i>=date('Y')-5; $i-=1){
                                ?>
                                    <option value="{{ $i }}">{{ $i }}</option>
                                <?php } ?>
                            </select>
                            <span class="text-danger error" style="font-size: 12px;" id="priode_alert"></span>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Auditee</label>
                            <select name="prodi" id="prodi" class="form-control" required>
                                <option value="">--Pilih--</option>
                                <option value="Prodi Teknologi Rekayasa Bandar Udara">Prodi Teknologi Rekayasa Bandar Udara</option>
                                <option value="Prodi Manajemen Bandar Udara">Prodi Manajemen Bandar Udara</option>
                                <option value="Prodi Penyelamatan dan Pemadam Kebakaran Penerbangan">Prodi Penyelamatan dan Pemadam Kebakaran Penerbangan</option>
                                <option value="Perguruan Tinggi">Perguruan Tinggi</option>
                            </select>
                            <span class="text-danger error" style="font-size: 12px;" id="prodi_alert"></span>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">PIC Auditee</label>
                            <select name="pic_auditee" id="pic_auditee" class="form-control" required>
                                <option value="">--Pilih--</option>
                                    @foreach ($audites as $auditee)
                                        <option value="{{ $auditee->id }}">{{ $auditee->name }}</option>
                                    @endforeach
                            </select>
                            <span class="text-danger error" style="font-size: 12px;" id="pic_auditee"></span>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">PIC Auditee2</label>
                            <select name="pic_auditee2" id="pic_auditee2" class="form-control" required>
                                <option value="">--Pilih--</option>
                                    @foreach ($audites as $auditee)
                                        <option value="{{ $auditee->id }}">{{ $auditee->name }}</option>
                                    @endforeach
                            </select>
                            <span class="text-danger error" style="font-size: 12px;" id="pic_auditee"></span>
                        </div>

                        <div class="form-group">
                            <label>Instrumen AMI</label>
                                <select name="kurikulum_instrumen_id" id="kurikulum_instrumen_id" class="form-control ">
                                    <option value="">-Pilih-</option>
                                    @foreach ($kurikulums as $kr)
                                        <option value="{{ $kr->id }}">{{ $kr->nama_kurikulum }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error" style="font-size: 12px;" id="kurikulum_instrumen_id_alert"></span>
                            </div>

                        <fieldset class="form-group border p-3">
                            <legend class="w-auto px-2">Jadwal AMI</legend>
                            <div class="form-group">
                                <label>Tgl Awal Upload</label>
                                <input name="tgl_awal_upload" id="tgl_awal_upload" type="date" class="form-control">
                                <span class="text-danger error" style="font-size: 12px;" id="tgl_awal_upload_alert"></span>
                              </div>
                
                              <div class="form-group">
                                <label>Tgl Akhir Upload</label>
                                <input name="tgl_akhir_upload" id="tgl_akhir_upload" type="date" class="form-control">
                                <span class="text-danger error" style="font-size: 12px;" id="tgl_akhir_upload_alert"></span>
                              </div>

                              {{-- <div class="form-group">
                                <label>Link Unggahan Dokumen</label>
                                <input name="link_upload_dokumen" id="link_upload_dokumen" type="url" class="form-control">
                                <span class="text-danger error" style="font-size: 12px;" id="link_upload_dokumen_alert"></span>
                              </div> --}}
                              <div class="form-group">
                                <label>Tgl Awal Penilaian</label>
                                <input name="tgl_awal_penilaian" id="tgl_awal_penilaian" type="date" class="form-control">
                                <span class="text-danger error" style="font-size: 12px;" id="tgl_awal_penilaian_alert"></span>
                              </div>
                
                              <div class="form-group">
                                <label>Tgl Akhir Penilaian</label>
                                <input name="tgl_akhir_penilaian" id="tgl_akhir_penilaian" type="date" class="form-control">
                                <span class="text-danger error" style="font-size: 12px;" id="tgl_akhir_penilaian_alert"></span>
                              </div>
                        </fieldset>
                        <fieldset class="form-group border p-3">
                            <legend class="w-auto px-2">Auditor</legend>
                            <div class="form-group">
                            <label>Auditor 1</label>
                                <select name="auditor_satu" id="auditor_satu" class="form-control ">
                                    <option value="">-Pilih-</option>
                                    @foreach ($auditors as $aud1)
                                        <option value="{{ $aud1->id }}">{{ $aud1->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error" style="font-size: 12px;" id="auditor_satu_alert"></span>
                            </div>
                            <div class="form-group">
                            <label>Auditor 2</label>
                                <select name="auditor_dua" id="auditor_dua" class="form-control">
                                    <option value="">-Pilih-</option>
                                    @foreach ($auditors as $aud2)
                                        <option value="{{ $aud2->id }}">{{ $aud2->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error" style="font-size: 12px;" id="auditor_dua_alert"></span>
                            </div>
                            <div class="form-group">
                            <label>Auditor 3</label>
                                <select id="auditor_tiga" name="auditor_tiga" class="form-control ">
                                    <option value="">-Pilih-</option>
                                    @foreach ($auditors as $aud1)
                                        <option value="{{ $aud1->id }}">{{ $aud1->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error" style="font-size: 12px;" id="auditor_tiga_alert"></span>
                            </div>
                        </fieldset>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Aktifkan?</label>
                            <select class="form-control" name="status_aktif" id="status_aktif">
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                            <span class="text-danger error" style="font-size: 12px;" id="status_aktif_alert"></span>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Kirim WA?</label>
                            <select class="form-control" name="kirim_wa" id="kirim_wa">
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                            <span class="text-danger error" style="font-size: 12px;" id="kirim_wa_alert"></span>
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
            $("#myTable").DataTable({
                "ordering": false,
                ajax: '/data-jadwal_ami',
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
                        data: "judul"
                    },
                    {
                        render: function(data, type, row, meta) {
                            return `
                                
                                    <p>${row.nama_kurikulum}</p>
                                    <p style="display:none">Link Unggah Dok. : <a target="_blank" href=${row.link_upload_dokumen}>${row.link_upload_dokumen}</a></p>
                            `
                        }
                    },
                    {
                        render: function(data, type, row, meta) {
                            return `
                                <ul>
                                    <li>${row.auditor1}</li>
                                    <li>${row.auditor2}</li>
                                    <li>${row.auditor3}</li>
                                </ul>
                            `
                        }
                    },
                    {
                        render: function(data, type, row, meta) {
                            return `
                                
                                    <p>${row.prodi}</p>
                                    <p>PIC : ${row.nama_pic_auditee}</p>
                                    <p>PIC 2 : ${row.nama_pic_2}</p>
                            `
                        }
                    },
                    {
                        render: function(data, type, row, meta) {
                            return `
                                ${row.tgl_awal_upload} s/d ${row.tgl_akhir_upload}
                            `
                        }
                    },
                    {
                        render: function(data, type, row, meta) {
                            return `
                                ${row.tgl_awal_penilaian} s/d ${row.tgl_akhir_penilaian}
                            `
                        }
                    },
                    {
                        render: function(data, type, row, meta) {
                            
                            if (row.status_aktif == "1") {
                                return `<span class="badge badge-success">Ya</span>`
                            } else if (row.status_aktif == "0") {
                                return `<span class="badge badge-danger">Tidak</span>`
                            }
                        }
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
                modal.find('#judul').val(cokData[0].judul)
                modal.find('#priode').val(cokData[0].priode)
                modal.find('#prodi').val(cokData[0].prodi)
                modal.find('#tgl_awal_upload').val(cokData[0].tgl_awal_upload)
                modal.find('#tgl_akhir_upload').val(cokData[0].tgl_akhir_upload)
                modal.find('#tgl_awal_penilaian').val(cokData[0].tgl_awal_penilaian)
                modal.find('#tgl_akhir_penilaian').val(cokData[0].tgl_akhir_penilaian)
                modal.find('#auditor_satu').val(cokData[0].auditor_satu)
                modal.find('#auditor_dua').val(cokData[0].auditor_dua)
                modal.find('#auditor_tiga').val(cokData[0].auditor_tiga)
                modal.find('#link_upload_dokumen').val(cokData[0].link_upload_dokumen)
                modal.find('#pic_auditee').val(cokData[0].pic_auditee)
                modal.find('#pic_auditee2').val(cokData[0].pic_auditee2)
                modal.find('#kurikulum_instrumen_id').val(cokData[0].kurikulum_instrumen_id)
                modal.find('#status_aktif').val(cokData[0].status_aktif)
            }
        })

        form.onsubmit = (e) => {

            let formData = new FormData(form);

            e.preventDefault();

            document.getElementById("tombol_kirim").disabled = true;

            axios({
                    method: 'post',
                    url: formData.get('id') == '' ? '/store-jadwal_ami' : '/update-jadwal_ami',
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

                    } else if(res.data.responCode == 0) {

                        console.log('terjadi error');
                    } else if(res.data.responCode == 2){
                        Swal.fire({
                            icon: 'error',
                            title: 'GAGAL!',
                            text: res.data.respon,
                            timer: 3000,
                            showConfirmButton: false
                        })
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
                    axios.post('/delete-jadwal_ami', {
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

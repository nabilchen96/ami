<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Acara Audit Mutu Internal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-header {
            vertical-align: middle;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <table class="table table-bordered table-header">
            <tr>
                <td width="25%" class="text-center" rowspan="5">
                    <img src="https://poltekbangplg.ac.id/wp-content/uploads/2021/02/logo_poltekbangplg_new400x258.png" alt="" style="width: 150px; height: 150px;">
                </td>
                <td width="25%" class="text-center" rowspan="3">
                    <b>
                        POLTEKBANG <br>
                        PALEMBANG
                    </b>
                </td>
                <td width="15%">Kode/No</td>
                <td width="15%">: FM-SPMI-PLP-NAK-02.09</td>
            </tr>
            <tr>
                <td>
                    Tgl. <br>
                    Pembuatan
                </td>
                <td>: 31 Agustus 2109</td>
            </tr>
            <tr>
                <td>
                    Tgl. Revisi
                </td>
                <td>: -</td>
            </tr>
            <tr>

                <td class="text-center" rowspan="3">
                    <b>
                        DOKUMEN MUTU <br>
                        SISTEM PENJAMIN <br>
                        MUTU INTERNAL <br>
                    </b>
                </td>
                <td>Tgl. berlaku</td>
                <td>: 31 Agustus 2019</td>
            </tr>
            <tr>

                <td rowspan="2" colspan="2" class="text-center">
                    <b>BERITA ACARA AUDIT MUTU INTERNAL</b>
                </td>
            </tr>
            <tr>
                <td>Hal: 1 dari 1</td>
            </tr>
        </table>
        <table class="table table-borderless table-header">
            <tr>
                <td colspan="2" class="text-center">
                    <h4>BERITA ACARA AUDIT MUTU INTERNAL</h4>
                    {{!@$dataBA->nomor_surat ? 'No. Belum diisi' : @$dataBA->nomor_surat }}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Dengan ini dinyatakan bahwa pada tanggal {{ date('d', strtotime(@$dataBA->tgl_awal)) }} -
                    {{ date('d', strtotime(@$dataBA->tgl_akhir)) }}
                    {{ date('M', strtotime(@$dataBA->tgl_awal)) }}
                    {{ date('Y', strtotime(@$dataBA->tgl_awal)) }}, Auditor:
                </td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>: {{@$getUser->name}}</td>
            </tr>
            <tr>
                <td>NIP/NIDN</td>
                <td>: {{@$getUser->nip}}</td>
            </tr>
            <tr>
                <td colspan="2">
                    Telah melaksanakan audit mutu internal (AMI):
                </td>
            </tr>
            <tr>
                <td>Nama Ka. Prodi/Perwakilan</td>
                <td>: {{@$dataBA->nama_auditee}}</td>
            </tr>
            <tr>
                <td>NIP/NIDN</td>
                <td>: {{@$dataBA->nip_auditee}}</td>
            </tr>
            <tr>
                <td>Program Studi</td>
                <td>: {{@$jadwal->prodi}}</td>
            </tr>
            <tr>
                <td colspan="2">
                    Catatan Pelaksanaan Audit : <br>
                    <?php echo @$dataBA->isi_ba ?>
                </td>
            </tr>
        </table>
        <table class="table table-borderless">
            <tr>
                <td width="50%" class="text-center">
                    <b>Auditor</b>
                    <br>
                    <img src="{{ asset('storage/' . @$dataBA->ttd_auditor) }}" alt="" style="width: 200px; height:85px;">
                    <br>
                    ({{@$getUser->name}})
                </td>
                <td width="55%" class="text-center">
                    <b>Auditee</b>
                    <br>
                    <img src="{{ asset('storage/' . @$dataBA->ttd_auditee) }}" alt="" style="width: 200px; height:85px;">
                    <br>
                    ({{@$dataBA->nama_auditee}})
                </td>
            </tr>
        </table>
    </div>
</body>
<script>
    window.print()
</script>
</html>
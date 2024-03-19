@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="container-fluid">

    <div class="mt-5 mb-5">
        {!! anchor($ci->session->userdata('urlback'), '<i class="fas fa-arrow-left"></i> Kembali', ['class' => "btn
        btn-light-primary font-weight-bold shadow"]) !!}
    </div>

    <div class="card mt-5" data-aos="fade-down">
        <div class="card-header bg-secondary font-weight-bold">
            Informasi Pertanyaan
        </div>
        <div class="card-body">

            <h4>Klasifikasi Survei: <span class="text-primary">{{ $nama_klasifikasi }}</span></h4>
            <h4>Jenis Pelayanan: <span class="text-primary">{{ $nama_jenis_pelayanan }}</span></h4>
            <div>
                {!!
                anchor(base_url().'pertanyaan-unsur-pelayanan/preview/'.$ci->uri->segment(3).'/'.$ci->uri->segment(4),
                '<i class="fas fa-file"></i> Preview Pertanyaan', ['class' => "btn btn-primary btn-sm font-weight-bold
                shadow", 'target' => '_blank']) !!}
            </div>

        </div>
    </div>

    <div class="card mt-5" data-aos="fade-down">
        <div class="card-header bg-secondary font-weight-bold">
            Unsur Pelayanan
        </div>
        <div class="card-body">

        <div class="row mb-5">
                <div class="col-md-6">

                </div>
                <div class="col-md-6 text-right">
                    {!!
                    anchor(base_url().'pertanyaan-unsur-pelayanan/add-unsur/'.$ci->uri->segment(3).'/'.$ci->uri->segment(4),
                    '<i class="fas fa-plus"></i> Tambah Unsur Pelayanan', ['class' => 'btn btn-primary btn-sm
                    font-weight-bold shadow-lg']) !!}
                </div>
        </div>

        <input type="hidden" name="jenis_pelayanan" id="jenis_pelayanan" value="{{ $ci->uri->segment(4) }}">

        <div class="table-responsive">
            <table id="table_unsur_pelayanan" class="table table-bordered table-hover" cellspacing="0" width="100%">
                <thead class="bg-secondary">
                    <tr>
                        <th>No.</th>
                        <th>Nomor Unsur</th>
                        <th>Nama Unsur</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        </div>
    </div>

    <div class="card mt-5" data-aos="fade-down">
        <div class="card-header bg-secondary font-weight-bold">
            {{ $title }}
        </div>
        <div class="card-body">

            <div class="row mb-5">
                <div class="col-md-6">

                </div>
                <div class="col-md-6 text-right">
                    {!!
                    anchor(base_url().'pertanyaan-unsur-pelayanan/add/'.$ci->uri->segment(3).'/'.$ci->uri->segment(4),
                    '<i class="fas fa-plus"></i> Tambah Pertanyaan Unsur Pelayanan', ['class' => 'btn btn-primary btn-sm
                    font-weight-bold shadow-lg']) !!}
                </div>
            </div>

            {{-- <p>
                Menu Pertanyaan Unsur Pelayanan adalah semua data pertanyaan unsur. Anda bisa mensortirnya melalui menu filter.
            </p>
            <div class="text-right mb-3">
                @php
                echo anchor(base_url().'pertanyaan-unsur-pelayanan/add', 'Tambah Pertanyaan Unsur Pelayanan', ['class' => 'btn btn-primary btn-sm font-weight-bold shadow-lg'])
                @endphp
            </div>

            <form id="form-filter" class="">
            <div class="row mb-5">
                <div class="col-md-6">
                    
                    <label for="jenis_pelayanan" class="form-label font-weight-bold text-primary">Filter Klasifikasi dan Pelayanan</label>
                    <select name="jenis_pelayanan" id="jenis_pelayanan" class="form-control" onchange="updateUnit();">
                        <option value="">Please Select</option>
                        @php
                            $ci->db->select('*, jenis_pelayanan.id AS id_jenis_pelayanan');
                            $ci->db->from('klasifikasi_survei');
                            $ci->db->join('jenis_pelayanan', 'jenis_pelayanan.id_klasifikasi_survei = klasifikasi_survei.id');
                            $jenis_pelayanan = $ci->db->get();
                        @endphp
                        @foreach ($jenis_pelayanan->result() as $value)
                            <option value="{{ $value->id_jenis_pelayanan }}">{{ $value->nama_klasifikasi_survei }} --
            {{ $value->nama_jenis_pelayanan_responden }}</option>
            @endforeach
            </select>

        </div>

    </div>
    </form>
    <script>
        function updateUnit() {
            table.ajax.reload(null, false);
        }
    </script> --}}

    <input type="hidden" name="jenis_pelayanan" id="jenis_pelayanan" value="{{ $ci->uri->segment(4) }}">

    <div class="table-responsive">
        <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%">
            <thead class="bg-secondary">
                <tr>
                    <th>No.</th>
                    {{-- <th>Klasifikasi & Jenis Pelayanan</th> --}}
                    <th>Unsur Pelayanan</th>
                    <th>Isi Pertanyaan Unsur</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
</div>

<div class="card mt-5">
    <div class="card-header bg-secondary font-weight-bold">
        Pertanyaan Tambahan
    </div>
    <div class="card-body">

        <div class="text-right mb-3">
            {!!
            anchor(base_url().'pertanyaan-unsur-pelayanan/add-pertanyaan-tambahan/'.$ci->uri->segment(3).'/'.$ci->uri->segment(4),
            '<i class="fas fa-plus"></i> Tambah Pertanyaan Tambahan', ['class' => 'btn btn-primary btn-sm
            font-weight-bold shadow-lg']) !!}
        </div>

        <div class="table-responsive">
            <table id="table_pertanyaan_tambahan" class="table table-bordered table-hover" cellspacing="0" width="100%">
                <thead class="bg-secondary">
                    <tr>
                        <th>No.</th>
                        <th>Unsur Pelayanan</th>
                        <th>Isi Pertanyaan Tambahan</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mt-5">
    <div class="card-header bg-secondary font-weight-bold">
        Pertanyaan Harapan
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table_pertanyaan_harapan" class="table table-bordered table-hover" cellspacing="0" width="100%">
                <thead class="bg-secondary">
                    <tr>
                        <th>No.</th>
                        <th>Unsur Pelayanan</th>
                        <th>Isi Pertanyaan Harapan</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>

<?php
foreach ($pertanyaan_harapan as $row) {
?>
    <div class="example-modal">
        <div id="pertanyaan_tambahan<?php echo $row->id ?>" class="modal fade" role="dialog" style="display:none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-secondary">
                        <h5 class="font-weight-bold">Detail Jawaban</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">

                        <div class="" id="kt_blockui_content">

                            <p><b><?php echo $row->isi_pertanyaan_unsur ?></b></p>

                            <div class="ml-3">
                                <li><?php echo $row->pilihan_1 ?></li>
                                <li><?php echo $row->pilihan_2 ?></li>
                                <li><?php echo $row->pilihan_3 ?></li>
                                <li><?php echo $row->pilihan_4 ?></li>
                            </div>

                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>

@endsection

@section('javascript')
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>
<script>
    $(document).ready(function() {
        table_unsur_pelayanan = $('#table_unsur_pelayanan').DataTable({

            "processing": true,
            "serverSide": true,
            "lengthMenu": [
                [5, 10, 25, 50, 100],
                [5, 10, 25, 50, 100]
            ],
            "pageLength": 5,
            "order": [],
            "language": {
                "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
            },
            "ajax": {
                "url": "{{ base_url() }}pertanyaan-unsur-pelayanan/ajax-list-unsur-pelayanan",
                "type": "POST",
                "data": function(data) {
                    data.id_jenis_pelayanan = $('#jenis_pelayanan').val();
                }
            },

            "columnDefs": [{
                "targets": [-1],
                "orderable": false,
            }, ],

        });


        table = $('#table').DataTable({

            "processing": true,
            "serverSide": true,
            "lengthMenu": [
                [5, 10, 25, 50, 100],
                [5, 10, 25, 50, 100]
            ],
            "pageLength": 5,
            "order": [],
            "language": {
                "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
            },
            "ajax": {
                "url": "{{ base_url() }}pertanyaan-unsur-pelayanan/ajax-list-pertanyaan-unsur-pelayanan",
                "type": "POST",
                "data": function(data) {
                    data.id_jenis_pelayanan = $('#jenis_pelayanan').val();
                }
            },

            "columnDefs": [{
                "targets": [-1],
                "orderable": false,
            }, ],

        });


        table_pertanyaan_harapan = $('#table_pertanyaan_harapan').DataTable({

            "processing": true,
            "serverSide": true,
            "lengthMenu": [
                [5, 10, 25, 50, 100],
                [5, 10, 25, 50, 100]
            ],
            "pageLength": 5,
            "order": [],
            "language": {
                "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
            },
            "ajax": {
                "url": "{{ base_url() }}pertanyaan-unsur-pelayanan/ajax-list-pertanyaan-harapan",
                "type": "POST",
                "data": function(data) {
                    data.id_jenis_pelayanan = $('#jenis_pelayanan').val();
                }
            },

            "columnDefs": [{
                "targets": [-1],
                "orderable": false,
            }, ],

        });

        table_pertanyaan_tambahan = $('#table_pertanyaan_tambahan').DataTable({

            "processing": true,
            "serverSide": true,
            "lengthMenu": [
                [5, 10, 25, 50, 100],
                [5, 10, 25, 50, 100]
            ],
            "pageLength": 5,
            "order": [],
            "language": {
                "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
            },
            "ajax": {
                "url": "{{ base_url() }}pertanyaan-unsur-pelayanan/ajax-list-pertanyaan-tambahan",
                "type": "POST",
                "data": function(data) {
                    data.id_jenis_pelayanan = $('#jenis_pelayanan').val();
                }
            },

            "columnDefs": [{
                "targets": [-1],
                "orderable": false,
            }, ],

        });

    });

    $('#btn-filter').click(function() {
        table.ajax.reload();
    });
    $('#btn-reset').click(function() {
        $('#form-filter')[0].reset();
        table.ajax.reload();
    });



    function delete_data_unsur(id_unsur_pelayanan) {
        if (confirm('Are you sure delete this data?')) {
            $.ajax({
                url: "{{ base_url() }}pertanyaan-unsur-pelayanan/delete-unsur/" +
                    id_unsur_pelayanan,
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {
                        Swal.fire(
                            'Informasi',
                            'Berhasil menghapus data',
                            'success'
                        );
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        Swal.fire(
                            'Informasi',
                            'Hak akses terbatasi. Bukan akun administrator.',
                            'warning'
                        );
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });

        }
    }

    function delete_data(id) {
        if (confirm('Are you sure delete this data?')) {
            $.ajax({
                url: "{{ base_url() }}pertanyaan-unsur-pelayanan/delete/" + id,
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {

                        table.ajax.reload();

                        Swal.fire(
                            'Informasi',
                            'Berhasil menghapus data',
                            'success'
                        );
                    } else {
                        Swal.fire(
                            'Informasi',
                            'Hak akses terbatasi. Bukan akun administrator.',
                            'warning'
                        );
                    }


                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });

        }
    }

    function delete_table(id_pertanyaan_terbuka) {
        if (confirm('Are you sure delete this data?')) {
            $.ajax({
                url: "{{ base_url() }}pertanyaan-unsur-pelayanan/delete-pertanyaan-tambahan/" +
                    id_pertanyaan_terbuka,
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {
                        Swal.fire(
                            'Informasi',
                            'Berhasil menghapus data',
                            'success'
                        );
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        Swal.fire(
                            'Informasi',
                            'Hak akses terbatasi. Bukan akun administrator.',
                            'warning'
                        );
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });

        }
    }
</script>
@endsection
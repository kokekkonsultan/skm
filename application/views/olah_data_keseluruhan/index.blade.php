@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />

@endsection

@section('content')
<div class=" container-fluid">

    <div class="card card-custom bgi-no-repeat gutter-b aos-init aos-animate" style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/rhone-2.svg)" data-aos="fade-down">
        <div class="card-body d-flex align-items-center">
            <div>
                <h3 class="text-white font-weight-bolder line-height-lg mb-5">
                {{strtoupper($title)}}
                </h3>
            </div>
        </div>
    </div>


        @if($induk->num_rows() > 0)
        <div class="card-deck aos-init aos-animate mb-5">
                <div class="card card-body">

                    <div class="text-center">
                        <span class="font-weight-bold text-primary">Nilai Indeks Keseluruhan</span>
                        <br>
                        <span class="font-weight-bold" style="font-size:30px;"><b>{{ROUND($nilai_induk->nilai_indeks, 3)}}</b></span>
                    </div>

                </div>
                
                <div class="card card-body">

                    <span class="font-weight-bold text-primary">Survei Yang Menjadi Obyek</span>

                    <ul class="mt-3">
                        @php
                        $id_object = implode(", ", unserialize($nilai_induk->id_object_indeks));
                        @endphp
                        @foreach($ci->db->query("SELECT * FROM manage_survey WHERE id IN ($id_object)")->result() as $row)
                        <li>{{$row->survey_name}} - {{$row->organisasi}}</li>
                        @endforeach
                    </ul>
                    
                </div>
            </div>
        @endif


    <div class="card shadow aos-init aos-animate" data-aos="fade-up">
        <div class="card-body">

            <form class="form_default" action="<?php echo base_url() . 'olah-data-keseluruhan/proses-index' ?>" method="POST">

                <div class="checkbox-inline mb-5">
                    <label class="checkbox checkbox-lg checkbox-primary">
                        <input type="checkbox" class="checkAll font-weight-bold" name="checkAll" id="checkAll" />
                        <span></span><b class="text-primary">Pilih Semua</b>
                    </label>
                </div>

                <div class="table-responsive">
                    <table id="table" class="table table-bordered table-hover mt-5" cellspacing="0" width="100%">
                        <thead class="bg-gray-300">
                            <tr>
                                <th>No</th>
                                <th>Survei</th>
                                <th>Organisasi</th>
                                <th>Indeks</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

                <button type="button" class="btn btn-primary btn-sm mt-5 font-weight-bold" data-toggle="modal" data-target="#exampleModal">
                Generate Indeks
                </button>


                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content border border-primary">
                            <!-- <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div> -->
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold">Masukkan Nama Label Indeks <span class="text-danger">*</span></label>
                                    <input class="form-control" name="label" placeholder="Survei Kepuasan Masyarakat Tahun 2023" required>
                                </div>


                                <div class="text-right mt-5">
                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary btn-sm font-weight-bold tombolDefault">Simpan</button>
                                 </div>
                            </div>
                            
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>


@endsection

@section('javascript')
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>

<script>
    $(document).ready(function() {
        $("#checkAll").click(function() {
            $(".child").prop("checked", this.checked);
        });
    });
</script>


<script>
    var table;
    $(document).ready(function() {
        table = $("#table").DataTable({
            "processing": true,
            "serverSide": true,
            "lengthMenu": [
                [5, 10, -1],
                [5, 10, "Semua data"]
            ],
            "pageLength": 5,
            "ordering": true,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo base_url() . 'olah-data-keseluruhan/ajax-list' ?>",
                "type": "POST",
                "dataType": "json",
                "dataSrc": function(jsonData) {
                    return jsonData.data;
                },
                "data": function(data) {},

            },
            "columnDefs": [{
                "targets": [0],
                "orderable": false,
            }, ],

        });
    });

    $('#btn-filter').click(function() {
        $('#checkAll').prop('checked', false);
        table.ajax.reload();
    });
    $('#btn-reset').click(function() {
        $('#form-filter')[0].reset();
        $('#checkAll').prop('checked', false);
        table.ajax.reload();
    });
</script>




<script>
$('.form_default').submit(function(e) {
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolDefault').attr('disabled', 'disabled');
            $('.tombolDefault').html(
                '<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

            Swal.fire({
                title: 'Memproses data',
                html: 'Mohon tunggu sebentar. Sistem sedang melakukan request anda.',
                allowOutsideClick: false,
                onOpen: () => {
                    swal.showLoading()
                }
            });

        },
        complete: function() {
            $('.tombolDefault').removeAttr('disabled');
            $('.tombolDefault').html('Simpan');
        },
        error: function(e) {
            Swal.fire(
                    'Informasi',
                    'Gagal memproses data!',
                    'error'
                );
                window.setTimeout(function() {
                    location.reload()
                }, 2000);
        },

        success: function(data) {
            // if (data.gagal) {
            //     Swal.fire(
            //         'Informasi',
            //         'Gagal memproses data!',
            //         'error'
            //     );
            // }
            if (data.sukses) {
                $('#checkAll').prop('checked', false);
                Swal.fire(
                    'Informasi',
                    'Berhasil mendapatkan nilai indeks',
                    'success'
                );

                window.setTimeout(function() {
                    location.reload()
                }, 2000);
            }
        }
    });
    return false;
});
</script>

@endsection
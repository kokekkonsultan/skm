@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />

<style type="text/css">
    /* .dataTables_length {
        display: none
    } */
</style>
@endsection

@section('content')

<div class="container-fluid">
    @include("include_backend/partials_no_aside/_inc_menu_repository")

    <div class="row mt-5">
        <div class="col-md-3">
            @include('manage_survey/menu_data_survey')
        </div>
        <div class="col-md-9">

        <div class="card card-custom bgi-no-repeat gutter-b"
                style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/taieri.svg)"
                data-aos="fade-down">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <h3 class="text-white font-weight-bolder line-height-lg mb-5">
                            {{strtoupper($title)}}
                        </h3>

                        @if ($is_question == 1)
                        <button type="button" class="btn btn-primary font-weight-bold shadow-lg btn-sm"
                            data-toggle="modal" data-target="#exampleModal">
                            <i class="fas fa-plus"></i> Tambah Pertanyaan
                        </button>
                        @endif
                    </div>
                </div>
            </div>


            <div class="card card-custom card-sticky" data-aos="fade-down">
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover example" cellspacing="0" width="100%" style="font-size: 12px;">
                            <thead class="bg-secondary">
                                <tr>
                                    <th width="5%">No.</th>
                                    <th>Unsur Pelayanan</th>
                                    <th>Isi Pertanyaan</th>
                                    <th>Pilihan Jawaban</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $no = 1;
                                @endphp
                                @foreach($unsur_pelayanan->result() as $value)

                                @php
                                $table_identity = $profiles->table_identity;
                                $cek_unsur = $ci->db->get_where("unsur_pelayanan_$table_identity", array('id_parent' => $value->id_unsur));


                                if ($profiles->skala_likert == 5) {
                                    $pilihan_5 = '<label><input type="radio">&ensp;' . $value->pilihan_5 . '&emsp;</label>';
                                    $pilihan_terakhir = $value->pilihan_5;
                                } else {
                                    $pilihan_5 = '';
                                    $pilihan_terakhir = $value->pilihan_4;
                                }


                                if ($value->pilihan == 2) {
                                    $jawaban = '<label><input type="radio">&ensp;' . $value->pilihan_1 .
                                        '&emsp;</label><label><input type="radio">&ensp;' . $value->pilihan_2 .
                                        '&emsp;</label><label><input type="radio">&ensp;' . $value->pilihan_3 .
                                        '&emsp;</label><label><input type="radio">&ensp;' . $value->pilihan_4 .
                                        '&emsp;</label>' . $pilihan_5;
                                } else if ($value->pilihan == 1) {
                                    $jawaban = '<label><input type="radio">&ensp;' . $value->pilihan_1 .
                                        '&emsp;</label><label><input type="radio">&ensp;' . $pilihan_terakhir .
                                        '&emsp;</label>';
                                } else {
                                    $jawaban = '<span class="text-danger">Unsur ini memiliki Sub.</span>';
                                }
                                @endphp
                                
                                
                                <tr data-index="{{ $value->id_unsur_pelayanan }}" data-position="{{ $value->nomor_unsur }}">
                                    <td class="text-center">
                                        <!-- <i class="fa fa-arrows-alt text-primary"></i> --> {!!$no++!!}</td>
                                    <td>
										{!!'<b>' . $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan . '</b>'!!} 
										

										<button type="button" class="btn btn-link" data-toggle="modal" data-target="#exampleModal{{ $no }}" title="Ubah nomor unsur dan nama unsur">
											<i class="flaticon-edit-1"></i>
										</button>
										  
										  <!-- Modal -->
										  <div class="modal fade" id="exampleModal{{ $no }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
											<div class="modal-dialog">
											  <div class="modal-content">
												<div class="modal-header">
												  <h5 class="modal-title" id="exampleModalLabel">Edit nomor unsur dan nama unsur</h5>
												  	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<i aria-hidden="true" class="ki ki-close"></i>
													</button>
												</div>

                                                <form action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-unsur/edit-unsur' ?>" method="POST" class="form_default">
												<div class="modal-body">
                                                
                                                    <input name="id" value="{{$value->id_unsur}}" hidden>
													<div class="form-group">
														<label>Nomor unsur dan nama unsur saat ini</label>
														<div class="input-group">
														 <div class="input-group-prepend"><span class="input-group-text">{{ $value->nomor_unsur }}</span></div>
														 <input type="text" class="form-control" placeholder="" value="{{ $value->nama_unsur_pelayanan }}" disabled />
														</div>
													</div>

													<div class="">
														<label>Inputkan nomor unsur dan nama unsur yang akan anda ubah pada bidang dibawah ini</label>
													</div>
													<div class="row">
														<div class="col-md-2">
															<input type="text" name="nomor_unsur" class="form-control" value="{{ $value->nomor_unsur }}" required />
														</div>
														<div class="col-md-10">
															<input type="text" name="nama_unsur_pelayanan" class="form-control" value="{{ $value->nama_unsur_pelayanan }}" required />
														</div>
													</div>

												</div>
												<div class="modal-footer">
												  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
												  <button type="submit" class="btn btn-primary tombolSimpanDefault">Simpan</button>
												</div>
                                                </form>

											  </div>
											</div>
										  </div>



									</td>
                                    <td>{!!$value->isi_pertanyaan_unsur!!}</td>
                                    <td>{!!$jawaban!!}</td>
                                    <td>
                                        <a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="{{ base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-unsur/edit/' . $value->id_unsur}}"><i class="fa fa-edit"></i> Edit</a>

                                
                                        @if($profiles->is_question == 1)
                                        <hr>
                                            @if ($cek_unsur->num_rows() == 0)
                                                <a class="btn btn-light-primary btn-sm font-weight-bold" href="javascript:void(0)" title="Hapus ' . $value->nama_unsur_pelayanan . '" onclick="delete_data({{$value->id_unsur}})"><i class="fa fa-trash"></i> Delete</a>
                                            @else
                                                <a class="btn btn-light-primary btn-sm font-weight-bold" onclick="cek()"><i class="fa fa-trash"></i> Delete</a>
                                            @endif
                                        @endif
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
















            <!-- 

            <div class="card card-custom card-sticky" data-aos="fade-down">

                <div class="card-body">

                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%"
                            style="font-size: 12px;">
                            <thead class="bg-secondary">
                                <tr>
                                    <th width="5%">No.</th>
                                    <th>Unsur Pelayanan</th>
                                    <th>Isi Pertanyaan</th>
                                    <th>Pilihan Jawaban</th>
                                    <th></th>
                                    <?php if ($is_question == 1) {
                                        echo '<th></th>';
                                    } ?>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <br>
                </div>
            </div> -->

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card-deck">
                    <a href="{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) .
                '/pertanyaan-unsur/add'}}" class="card card-body btn btn-outline-primary shadow">
                        <div class="text-center font-weight-bold">
                            <i class="fas fa-plus"></i><br>Tambah Pertanyaan Unsur
                        </div>
                    </a>

                    <a href="{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) .
                '/pertanyaan-unsur/add-sub'}}" class="card card-body btn btn-outline-primary shadow">
                        <div class="text-center font-weight-bold">
                            <i class="fas fa-plus"></i><br>Tambah Pertanyaan Sub Unsur
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>
<script src="{{ base_url() }}assets/themes/metronic/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script>
    $(document).ready(function() {
        $('.example').DataTable({
            "lengthMenu": [
                [-1],
                ["All"]
            ],
            "pageLength": -1,
            "destroy":true
        });
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
                $('.tombolSimpanDefault').attr('disabled', 'disabled');
                $('.tombolSimpanDefault').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');
                KTApp.block('#content_1', {
                    overlayColor: '#000000',
                    state: 'primary',
                    message: 'Processing...'
                });
                setTimeout(function() {
                    KTApp.unblock('#content_1');
                }, 1000);
            },
            complete: function() {
                $('.tombolSimpanDefault').removeAttr('disabled');
                $('.tombolSimpanDefault').html('Simpan');
            },
            error: function(e) {
                Swal.fire(
                    'Error !',
                    e,
                    'error'
                )
            },
            success: function(data) {
                if (data.validasi) {
                    $('.pesan').fadeIn();
                    $('.pesan').html(data.validasi);
                }
                if (data.sukses) {
                    toastr["success"]('Data berhasil disimpan');
                    window.setTimeout(function() {
                        location.reload()
                    }, 2000);
                }
            }
        })
        return false;
    });

    // $(document).ready(function() {
    //     table = $('#table').DataTable({

    //         "processing": true,
    //         "serverSide": true,
    //         "lengthMenu": [
    //             [5, 10, 25, 50, 100, -1],
    //             [5, 10, 25, 50, 100, "Semua data"]
    //         ],
    //         "pageLength": 5,
    //         "order": [],
    //         "language": {
    //             "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
    //         },
    //         "ajax": {
    //             "url": "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-unsur/ajax-list' ?>",
    //             "type": "POST",
    //             "data": function(data) {}
    //         },

    //         "columnDefs": [{
    //             "targets": [-1],
    //             "orderable": false,
    //         }, ],

    //     });
    // });

    // $('#btn-filter').click(function() {
    //     table.ajax.reload();
    // });
    // $('#btn-reset').click(function() {
    //     $('#form-filter')[0].reset();
    //     table.ajax.reload();
    // });


    function delete_data(id_pertanyaan_unsur) {
        if (confirm('Are you sure delete this data?')) {
            $.ajax({
                url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-unsur/delete/' ?>" +
                    id_pertanyaan_unsur,
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {

                        $('#table').DataTable().ajax.reload()

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
</script>

<script>
    function cek() {
        Swal.fire({
            icon: 'warning',
            title: 'Informasi',
            text: 'Unsur tidak dapat dihapus karna masih terdapat sub unsur turunan di bawahnya. Silahkan hapus sub unsur turunan terlebih dahulu!',
            allowOutsideClick: false,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Ya, Saya mengerti !',
        });
    }
</script>


<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('table tbody').sortable({
            update: function(event, ui) {
                $(this).children().each(function(index) {
                    if ($(this).attr('data-position') != (index + 1)) {
                        $(this).attr('data-position', (index + 1)).addClass('updated');
                    }
                });

                saveNewPositions();
            }
        });
    });

    function saveNewPositions() {
        var positions = [];
        $('.updated').each(function() {
            positions.push([$(this).attr('data-index'), $(this).attr('data-position')]);
            $(this).removeClass('updated');
        });
        console.log(positions);


        $.ajax({
            url: '<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-unsur/change-list' ?>',
            method: 'POST',
            dataType: 'text',
            data: {
                update: 1,
                positions: positions
            },
            success: function(response) {
                console.log(response);
                toastr["success"]('Urutan data berhasil diubah!');

                // var table = $('.example').DataTable();
                // table.clear().draw();
                // table.rows.add().draw();

                // Clear table
                
                // $('.example').DataTable().clear();
                // Redraw table
                // $('.example').DataTable().reload();
                // table.ajax.reload();
                window.setTimeout(function() {
                    location.reload()
                }, 1800);
            }
        });
    }
</script>
@endsection

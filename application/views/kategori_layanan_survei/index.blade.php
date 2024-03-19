@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="container-fluid">
    @include("include_backend/partials_no_aside/_inc_menu_repository")

    <div class="row mt-5">
        <div class="col-md-3">
            @include('manage_survey/menu_data_survey')
        </div>
        <div class="col-md-9">

            <div class="card card-custom bgi-no-repeat gutter-b" style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/taieri.svg)" data-aos="fade-down">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <h3 class="text-white font-weight-bolder line-height-lg mb-5">
                            {{strtoupper($title)}}
                        </h3>

                        @if ($profiles->is_question == 1)
                        <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add"><i class="fa fa-plus"></i> Tambah Kategori Layanan yang di Survei
                        </a>
                        @endif

                    </div>
                </div>
            </div>




            <div class="card card-custom card-sticky" data-aos="fade-down">
                <div class="card-body">

                    <form action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/kategori-layanan-survei/update-urutan' ?>" method="POST" class="form_default_urutan">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover example" style="width:100%">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th width="5%">Urutan</th>
                                        <th>Nama Kategori</th>
                                        <th>Status</th>

                                        @if ($profiles->is_question == 1)
                                        <th></th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $no = 0;
                                    $total_kategori_layanan = $kategori_layanan->num_rows();
                                    @endphp
                                    @foreach($kategori_layanan->result() as $value)
                                    @php
                                    $no++;
                                    @endphp
                                    <tr>
                                        <td width="5%"><?php 
                                        for ($i = 1; $i <= $total_kategori_layanan; ++$i) {
                                            $selected = $no == $i ? 'selected' : '';
                                            $urutan[$no][] = '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
                                        }
                                        echo '<input value="' . $value->id . '" name="id[]" hidden>
                                        <select name="urutan[]" class="form-control-sm">'. implode("<br>", $urutan[$no]) .'</select>'; ?></td>
                                        <td>{{$value->nama_kategori_layanan}}</td>
                                        <td><span class="badge badge-{{$value->is_active == 1 ? 'info' : 'danger'}}">{{$value->is_active == 1 ? 'Digunakan' : 'Tidak digunakan'}}</span></td>

                                        @if ($profiles->is_question == 1)
                                        <td>
                                            <a class="btn btn-light-primary btn-sm" data-toggle="modal" data-target="#edit_{{$value->id}}"><i class="fa fa-edit"></i> Edit
                                            </a>

                                            <a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus {{$value->nama_kategori_layanan}}" onclick="delete_data({{$value->id}})"><i class="fa fa-trash"></i> Delete</a>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($profiles->is_question == 1)
                        @if($total_kategori_layanan > 0)
                            <button type="submit" class="btn btn-light-primary btn-sm mt-5 tombolSimpanUrutan">Simpan Urutan Kategori Layanan</button>
                        @endif
                        @endif
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>



<!-- MODAL ADD -->
<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <span class="modal-title" id="exampleModalLabel">Tambah Kategori Layanan</span>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/kategori-layanan-survei/add' ?>" method="POST" class="form_default">


                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Nama Kategori Layanan<span style="color: red;">*</span></label>
                        <input class="form-control" name="nama_kategori_layanan" value="" required>
                    </div>

                    {{-- <div class=" form-group">
                        <label class="col-form-label font-weight-bold">Status<span style="color: red;">*</span></label>

                        <select class="form-control" name="is_active" required>
                            <option value="">Please Select</option>
                            <option value="1">Digunakan</option>
                            <option value="2">Tidak digunakan</option>
                        </select>
                    </div> --}}

                    <div class="text-right mt-3">
                        {{-- <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button> --}}
                        <button type="submit" class="btn btn-primary btn-sm tombolSimpanDefault">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal EDIT -->
@foreach($kategori_layanan->result() as $row)
<div class="modal fade" id="edit_{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <span class="modal-title" id="exampleModalLabel">Ubah Layanan</s>
            </div>
            <div class="modal-body">
                <form action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/kategori-layanan-survei/edit' ?>" method="POST" class="form_default">

                    <input name="id" value="{{$row->id}}" hidden>


                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Nama Kategori Layanan<span style="color: red;">*</span></label>
                        <input class="form-control" name="nama_kategori_layanan" value="{{$row->nama_kategori_layanan}}" required>
                    </div>

                    <div class=" form-group">
                        <label class="col-form-label font-weight-bold">Status<span style="color: red;">*</span></label>

                        <select class="form-control" name="is_active" required>
                            <option value="1" {{ $row->is_active == 1 ? 'selected' : '' }}>Digunakan</option>
                            <option value="2" {{$row->is_active == 2 ? 'selected' : '' }}>Tidak digunakan</option>
                        </select>
                    </div>

                    <div class="text-right mt-3">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm tombolSimpanDefault">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('javascript')
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>
<script src="{{ base_url() }}assets/themes/metronic/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script>
    $(document).ready(function() {
        $('.example').DataTable();
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




    function delete_data(id) {
        if (confirm('Are you sure delete this data?')) {
            $.ajax({
                url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/kategori-layanan-survei/delete/' ?>" +
                    id,
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {

                        Swal.fire(
                            'Informasi',
                            'Berhasil menghapus data',
                            'success'
                        );

                        window.setTimeout(function() {
                            location.reload()
                        }, 2000);
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
    $('.form_default_urutan').submit(function(e) {

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            beforeSend: function() {
                // $('.tombolSimpanDefault').attr('disabled', 'disabled');
                // $('.tombolSimpanDefault').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');
                $('.tombolSimpanUrutan').attr('disabled', 'disabled');
                $('.tombolSimpanUrutan').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

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
                // $('.tombolSimpanDefault').removeAttr('disabled');
                // $('.tombolSimpanDefault').html('Simpan');
                $('.tombolSimpanUrutan').removeAttr('disabled');
                $('.tombolSimpanUrutan').html('Simpan Urutan Kategori Layanan');
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
                    // table.ajax.reload();
                    window.setTimeout(function() {
                        location.reload()
                    }, 2500);
                }
            }
        })
        return false;
    });
</script>
@endsection

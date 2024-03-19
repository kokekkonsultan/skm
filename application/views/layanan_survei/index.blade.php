@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
    type="text/css" />
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

                        <a class="btn btn-light-primary btn-sm">Tambah Pilihan Lainnya</a>

                        @if ($profiles->is_question == 1)
                        <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add"><i
                                class="fa fa-plus"></i> Tambah Layanan yang di Survei</a>
                        @endif


                        @if ($profiles->is_question == 1)
                        @if ($profiles->is_kategori_layanan_survei == 0)
                        <a class="btn btn-danger btn-sm" data-toggle="modal" data-target="#activate"><i
                                class="fa fa-toggle-on"></i> Aktifkan Kategori Layanan Survei
                        </a>
                        @else
                        <a class="btn btn-danger btn-sm" data-toggle="modal" data-target="#activate"><i
                                class="fa fa-toggle-on"></i> Non-Aktifkan Kategori Layanan Survei
                        </a>
                        @endif
                        @endif

                    </div>
                </div>
            </div>




            <div class="card card-custom card-sticky" data-aos="fade-down">
                <div class="card-body">

                    <form
                        action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/layanan-survei/update-urutan' ?>"
                        method="POST" class="form_default_urutan">
                        <div class="table-responsive">
                            <table id="table" class="table table-bordered table-hover example" style="width:100%">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th width="5%">Urutan</th>
                                        <th>Nama Layanan</th>
                                        @if ($profiles->is_kategori_layanan_survei == 1)
                                        <th>Nama Kategori</th>
                                        @endif
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                @php
                                $total_layanan = $layanan->num_rows();
                                @endphp

                            </table>
                        </div>

                        @if ($profiles->is_question == 1)
                        @if($total_layanan > 0)
                        <button type="submit" class="btn btn-light-primary btn-sm mt-5 tombolSimpanUrutan">Simpan Urutan
                            Layanan</button>
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
                <span class="modal-title" id="exampleModalLabel">Tambah Layanan</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <form
                action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/layanan-survei/add' ?>"
                method="POST" class="form_default">
                <div class="modal-body">

                    @if ($profiles->is_kategori_layanan_survei == 1)
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Kategori Layanan<span
                                style="color: red;">*</span></label>
                        @if ($kategori_layanan->num_rows() > 0)

                        <select name="id_kategori_layanan" class="form-control" required>
                            <option value="">Please Select</option>
                            @foreach($kategori_layanan->result() as $data)
                            <option value="{{$data->id}}">{{$data->nama_kategori_layanan}}</option>
                            @endforeach
                        </select>
                        @else
                        <div class="">
                            Kategori layanan survei masih kosong, silahkan tambahkan kategori layanan survei. <a
                                href="{{ base_url() }}{{ $ci->session->userdata('username') }}/{{ $ci->uri->segment(2) }}/kategori-layanan-survei">Tambah
                                kategori layanan survei</a>
                        </div>
                        @endif
                    </div>
                    @else
                    <input name="id_kategori_layanan" value="" hidden>
                    @endif

                    @if ($profiles->is_kategori_layanan_survei == 1)
                    @if ($kategori_layanan->num_rows() > 0)
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Nama Layanan<span
                                style="color: red;">*</span></label>
                        <input class="form-control" name="nama_layanan" value="" required>
                    </div>

                    {{-- <div class="text-right mt-3">
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary btn-sm tombolSimpanDefault">Simpan</button>
                        </div> --}}
                    @endif
                    @else
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Nama Layanan<span
                                style="color: red;">*</span></label>
                        <input class="form-control" name="nama_layanan" value="" required>

                        {{-- <div class="text-right mt-3">
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary btn-sm tombolSimpanDefault">Simpan</button>
                        </div> --}}
                    </div>
                    @endif


                    {{-- <div class=" form-group">
                        <label class="col-form-label font-weight-bold">Status<span style="color: red;">*</span></label>

                        <select class="form-control" name="is_active" required>
                            <option value="">Please Select</option>
                            <option value="1">Digunakan</option>
                            <option value="2">Tidak digunakan</option>
                        </select>
                    </div> --}}


                </div>
                <div class="modal-footer">
                    <div class="text-right mt-3">
                        {{-- <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button> --}}
                        <button type="submit" class="btn btn-primary btn-sm tombolSimpanDefault">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal EDIT -->
@foreach($layanan->result() as $row)
<div class="modal fade" id="edit_{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <span class="modal-title" id="exampleModalLabel">Ubah Layanan</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <form
                action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/layanan-survei/edit' ?>"
                method="POST" class="form_default">
                <div class="modal-body">

                    <input name="id" value="{{$row->id}}" hidden>
                    @if ($profiles->is_kategori_layanan_survei == 1)
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Kategori Layanan<span
                                style="color: red;">*</span></label>
                        <select name="id_kategori_layanan" class="form-control" required>
                            <option value="">Please Select</option>
                            @foreach($kategori_layanan->result() as $data)
                            <option value="{{$data->id}}"
                                {{ $data->id == $row->id_kategori_layanan ? 'selected' : '' }}>
                                {{$data->nama_kategori_layanan}}</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <input name="id_kategori_layanan" value="" hidden>
                    @endif

                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Nama Layanan<span
                                style="color: red;">*</span></label>
                        <input class="form-control" name="nama_layanan" value="{{$row->nama_layanan}}" required>
                    </div>

                    <div class=" form-group">
                        <label class="col-form-label font-weight-bold">Status<span style="color: red;">*</span></label>

                        <select class="form-control" name="is_active" required>
                            <option value="1" {{ $row->is_active == 1 ? 'selected' : '' }}>Digunakan</option>
                            <option value="2" {{$row->is_active == 2 ? 'selected' : '' }}>Tidak digunakan</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="text-right mt-3">
                        {{-- <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button> --}}
                        <button type="submit" class="btn btn-primary btn-sm tombolSimpanDefault">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<div class="modal fade" id="activate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <span class="modal-title" id="exampleModalLabel">Kategori Layanan Survei</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form
                    action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/layanan-survei/activate' ?>"
                    method="POST" class="form_default_activate">

                    <div class="alert alert-secondary mb-5" role="alert">
                        <i class="flaticon-warning"></i> Jika Anda mengaktifkan kategori layanan survei, data layanan
                        survei Anda yang sudah ada akan dihapus. Silahkan mencadangkan data layanan survei Anda terlebih
                        dahulu.
                    </div>
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Kategori Layanan Survei<span
                                style="color: red;">*</span></label>
                        <span class="switch switch-icon">
                            <label>
                                <input type="checkbox" id="is_kategori_layanan_survei" name="is_kategori_layanan_survei"
                                    @if ($profiles->is_kategori_layanan_survei == 1) checked="checked" @endif value="1"
                                />
                                <span></span>
                            </label>
                        </span>
                    </div>
                    <input type="hidden" id="old_is_kategori_layanan_survei" name="old_is_kategori_layanan_survei"
                        value="{{ $profiles->is_kategori_layanan_survei }}" />

                    <div class="text-right mt-3">
                        {{-- <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button> --}}
                        <button type="submit" class="btn btn-primary btn-sm tombolSimpanDefault">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>
<script src="{{ base_url() }}assets/themes/metronic/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script>
// $(document).ready(function() {
//     $('.example').DataTable();
// });
$(document).ready(function() {
    table = $('#table').DataTable({

        "processing": true,
        "serverSide": true,
        "lengthMenu": [
            [5, 10, -1],
            [5, 10, "Semua data"]
        ],
        "pageLength": 10,
        "order": [],
        "language": {
            "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
        },
        "ajax": {
            "url": "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/layanan-survei/ajax-list' ?>",
            "type": "POST",
            "data": function(data) {}
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
</script>

<script>
$('.form_default_activate').submit(function(e) {
    var checkBox = document.getElementById("is_kategori_layanan_survei");
    var old_checkBox = document.getElementById("old_is_kategori_layanan_survei");

    if ((old_checkBox.value != 0) && (checkBox.checked == false)) {
        // var agree = confirm("Are you sure inactivate this data?");
        var agree = confirm("Anda yakin menon-aktifkan Kategori Layanan Survei ?");
        // confirm("Are you sure inactivate this data?");
    } else {
        // var agree = confirm("Are you sure you wish to continue?");
        var agree = confirm("Dengan menekan tombol OK, berarti Anda telah memahami ketentuan !");
        // confirm("Are you sure you wish to continue?");
    }

    e.preventDefault();

    if (agree) {
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            beforeSend: function() {
                $('.tombolSimpanDefault').attr('disabled', 'disabled');
                $('.tombolSimpanDefault').html(
                    '<i class="fa fa-spin fa-spinner"></i> Sedang diproses');
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
        return true;
    } else {
        return false;
    }
});


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
            url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/layanan-survei/delete/' ?>" +
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
            $('.tombolSimpanUrutan').html('Simpan Urutan Layanan');
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
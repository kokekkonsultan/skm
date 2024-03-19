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

            <!-- <div class="card card-custom bgi-no-repeat gutter-b"
                style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/taieri.svg)"
                data-aos="fade-down">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <h3 class="text-white font-weight-bolder line-height-lg mb-5">
                            {{strtoupper($title)}}
                        </h3>


                    </div>
                </div>
            </div> -->

            <h3>
                {{strtoupper($title)}}
            </h3>
            <p>
                Dengan melengkapi data dibawah ini, ada bisa melakukan broadcast melalui email.
            </p>
            <br>

            <div class="card card-custom card-sticky" data-aos="fade-down">
                <div class="card-header">
                    <div class="card-title">1. Data Responden</div>
                    <div class="cardtoolbar"></div>
                </div>
                <div class="card-body">

                    @if ($profiles->is_question == 2)

                    @endif

                    @if ($profiles->is_question == 2)
                    <p>
                        Anda bisa menambahkan alamat email dan telepon pada tabel data responden. Wajib mengisi kolom
                        email jika
                        anda akan melakukan broadcast melalui email.
                        <br><br>
                    </p>

                    <div class="mb-5 text-right">
                        <a href="{{ base_url() }}{{ $ci->session->userdata('username'); }}/{{ $ci->uri->segment(2) }}/data-prospek-survey/import"
                            class="btn btn-light-primary btn-sm font-weight-bold"><i
                                class="flaticon-folder-3 icon-md"></i>
                            Import</a>
                        <button class="btn btn-light-primary font-weight-bold shadow btn-sm" onclick="reload_table()"><i
                                class="flaticon-refresh icon-md"></i> Refresh</button>
                        <button class="btn btn-primary font-weight-bold shadow btn-sm" onclick="add_prospek()"><i
                                class="flaticon-add icon-md"></i>Tambah Prospek</button>
                    </div>

                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%"
                            style="font-size: 12px;">
                            <thead class="bg-gray-300">
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Lengkap</th>
                                    <th>Alamat</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th>Keterangan</th>
                                    {{-- <th></th> --}}
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    @if ($jumlah_prospek > 0)
                    <div class="mt-5">
                        <a href="javascript:void(0);" id="btn_empty_data" onclick="empty_data()"
                            class="text-danger font-weight-bold">Kosongkan Data</a>
                    </div>
                    @endif

                    @else
                    <p class="text-center">
                        Konfirmasi Pengisian Soal dan Pertanyaan Survey pada menu <b>Link Survey</b> belum dilakukan.
                        Setelah melakukan konfirmasi, anda bisa mengelola halaman ini.
                    </p>
                    @endif

                </div>
            </div>

            <div class="card card-custom card-sticky mt-5" data-aos="fade-down">
                <div class="card-header">
                    <div class="card-title">2. Isi Pesan</div>
                    <div class="cardtoolbar"></div>
                </div>
                <div class="card-body">
                    <p>
                        Isi pesan adalah kalimat yang akan ditampilkan pada email.
                    </p>
                    <a class="text-primary font-weight-bold" data-toggle="collapse" href="#collapseExample"
                        role="button" aria-expanded="false" aria-controls="collapseExample">
                        Contoh pengisian
                    </a>
                    <div class="collapse" id="collapseExample">
                        <div class="card card-body">
                            <h5>Contoh : </h5>
                            <div style="background-color: #F3F3F3; padding: 10px;">
                                <p>Kepada Yth.<br />
                                    Bapak/ Ibu $1</p>
                                <p>Kami Tim Survey Kepuasan Masyarakat $2, memohon kepada Bapak/ Ibu, untuk mengisi
                                    Kuesioner $3
                                    dengan link berikut ini $4. <br />
                                    Mohon diisi sebelum tanggal $5.</p>
                                <p>Atas kesedian dan partisipasinya kami ucapkan Terima Kasih.</p>
                            </div>
                            <br>
                            <p>
                                Anda bisa menggunakan tag berikut ini:
                            </p>
                            <ol>
                                <li><span class="text-danger">$1</span> untuk menampilkan nama lengkap responden</li>
                                <li><span class="text-danger">$2</span> untuk menampilkan nama organisasi anda</li>
                                <li><span class="text-danger">$3</span> untuk menampilkan nama survei</li>
                                <li><span class="text-danger">$4</span> untuk menampilkan link survei</li>
                                <li><span class="text-danger">$5</span> untuk menampilkan tanggal akhir survei</li>
                            </ol>
                        </div>
                    </div>
                    <div class="mt-5">
                    </div>

                    <br>
                    <br>

                    <form
                        action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/data-prospek-survey/update-email-prospek' ?>"
                        class="form_template_email" method="POST">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">Template Pesan <span
                                    style="color: red;">*</span></label>
                            {!! form_textarea($template_email_prospek) !!}

                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-light-primary font-weight-bold tombolSimpan"
                                onclick="tinyMCE.triggerSave();"><i class="flaticon-edit-1 icon-md"></i> Update
                                Template</button>
                        </div>
                    </form>

                </div>
            </div>

            <div class="card card-custom card-sticky mt-5" data-aos="fade-down">
                <div class="card-header">
                    <div class="card-title">3. Logo Header</div>
                    <div class="cardtoolbar"></div>
                </div>
                <div class="card-body">

                    @if ($logo_prospek == '')

                    <?php echo form_open_multipart($form_action); ?>

                    <div class="form-group row">
                        <?php echo form_label('File logo <span class="text-danger">**</span>', '', ['class' => 'col-sm-2 col-form-label font-weight-bold']); ?>
                        <div class="col-sm-10">
                            <?php echo form_upload($logo); ?><br>
                            <small id="" class="text-caption-label">Hanya diperbolehkan upload file dengan format *.jpg
                                atau *.png.
                                Ukuran gambar logo proporsional maksimal 200px x 200px.</small>
                        </div>
                    </div>

                    <div class="text-right">
                        <input type="submit" name="upload_logo" value="Simpan"
                            class="btn btn-light-primary font-weight-bold shadow">
                    </div>

                    <?php echo form_close(); ?>

                    @else
                    <img src="{{ base_url() }}prospek/public/assets/img/prospek/logo/{{ $logo_prospek }}" alt="">

                    <br>
                    <a href="{{ $link_hapus_logo }}" onclick="return confirm('Anda akan menghapus logo ?')"
                        class="text-danger font-weight-bold">Hapus Logo</a>
                    @endif

                </div>
            </div>

            <div class="card card-custom card-sticky mt-5" data-aos="fade-down">
                <div class="card-header">
                    <div class="card-title">4. Footer</div>
                    <div class="cardtoolbar"></div>
                </div>
                <div class="card-body">

                    <a class="text-primary font-weight-bold" data-toggle="collapse" href="#collapseFooter" role="button"
                        aria-expanded="false" aria-controls="collapseFooter">
                        Contoh pengisian
                    </a>
                    <div class="collapse" id="collapseFooter">
                        <div class="card card-body">
                            <h5>Contoh : </h5>
                            <div style="background-color: #F3F3F3; padding: 10px;">
                                <p>Jika ada keraguan terhadap survei ini, anda bisa menghubungi Tim Survey Kepuasan
                                    Masyarakat di
                                    nomor 081565656 atau melalui pesan email hallo@survei-kepuasan.com</p>
                            </div>
                        </div>
                    </div>

                    <br><br>

                    <form
                        action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/data-prospek-survey/update-email-footer-prospek' ?>"
                        class="form_template_footer_email" method="POST">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">Footer Email <span
                                    style="color: red;">*</span></label>
                            {!! form_textarea($template_email_footer_prospek) !!}

                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-light-primary font-weight-bold tombolSimpan"
                                onclick="tinyMCE.triggerSave();"><i class="flaticon-edit-1 icon-md"></i> Update
                                Template</button>
                        </div>
                    </form>

                </div>
            </div>

            <div class="card card-custom card-sticky mt-5" data-aos="fade-down">
                <div class="card-header">
                    <div class="card-title">5. Lampiran</div>
                    <div class="cardtoolbar"></div>
                </div>
                <div class="card-body">

                    @if ($file_lampiran == '')

                    <?php echo form_open_multipart($form_action); ?>

                    <div class="form-group row">
                        <?php echo form_label('File lampiran <span class="text-danger">**</span>', '', ['class' => 'col-sm-2 col-form-label font-weight-bold']); ?>
                        <div class="col-sm-10">
                            <?php echo form_upload($lampiran); ?><br>
                            <small id="" class="text-caption-label">Hanya diperbolehkan upload file dengan format
                                *.pdf</small>
                        </div>
                    </div>

                    <div class="text-right">
                        <input type="submit" name="upload_lampiran" value="Simpan"
                            class="btn btn-light-primary font-weight-bold shadow">
                    </div>
                    <?php echo form_close(); ?>

                    @else
                    <i class="flaticon-file-2 icon-lg"></i>
                    {{ $file_lampiran }}
                    <br><br><br><br>
                    <a href="{{ $link_hapus_lampiran }}" onclick="return confirm('Anda akan menghapus lampiran ?')"
                        class="text-danger font-weight-bold">Hapus Lampiran</a>
                    @endif

                </div>
            </div>

            <div class="mt-5">
                <h3>Preview</h3>
            </div>

            <div class="mt-5">
                <div class="card">
                    <div class="card-body" style="background-color: #F5F5F5;">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="text-center">
                                    @if ($logo_prospek != '')
                                    <img src="{{ base_url() }}prospek/public/assets/img/prospek/logo/{{ $logo_prospek }}"
                                        alt="">
                                    @endif
                                </div>
                                <div style="background-color: #FFFFFF; padding: 20px;">
                                    {!! $pt_email_prospek !!}
                                </div>
                                <div class="text-center mt-5"
                                    style="color: #B3B3B3; font-size: 10px; padding-left: 30px; padding-right: 30px;">
                                    {!! $pt_template_email_footer_prospek !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5 text-center">
                <a href="http://127.0.0.1:8000/prospek/{{ $uuid }}" target="_blank"
                    class="btn btn-info font-weight-bold btn-block shadow"><i class="flaticon-paper-plane icon-md"></i>
                    Broadcast</a>
            </div>

        </div>
    </div>

</div>


<div class="modal fade" id="modal_form_add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body form">

                <form action="#" id="form">

                    <div class="form-group">
                        <label for="">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control"
                            value="<?php echo $ci->form_validation->set_value('nama_lengkap'); ?>">
                        <span class="help-block"></span>
                    </div>

                    <div class="form-group">
                        <label for="">Alamat</label>
                        <input type="text" name="alamat" id="alamat" class="form-control"
                            value="<?php echo $ci->form_validation->set_value('alamat'); ?>">
                        <span class="help-block"></span>
                    </div>

                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                            value="<?php echo $ci->form_validation->set_value('email'); ?>">
                        <small class="text-primary">Email responden harus aktif dan email tidak boleh sama dengan email
                            yang lain.</small>
                        <span class="help-block"></span>
                    </div>

                    <div class="form-group">
                        <label for="">WhatsApp</label>
                        <input type="text" name="telepon" id="telepon" class="form-control"
                            value="<?php echo $ci->form_validation->set_value('telepon'); ?>">
                        <span class="help-block"></span>
                    </div>

                    <div class="form-group">
                        <label for="">Keterangan</label>
                        <input type="text" name="keterangan" id="keterangan" class="form-control"
                            value="<?php echo $ci->form_validation->set_value('keterangan'); ?>">
                        <span class="help-block"></span>
                    </div>

                </form>

            </div>
            <div class="modal-footer">

                <button type="button" id="btnSave" onclick="save_add()"
                    class="btn btn-primary font-weight-bold shadow-lg">Simpan</button>
                <button type="button" class="btn btn-light-primary font-weight-bold shadow-lg"
                    data-dismiss="modal">Batal</button>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_form_edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body form">

                <form action="#" id="form_edit">

                    <input type="hidden" value="" name="id" />

                    <div class="form-group">
                        <label for="">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control"
                            value="<?php echo $ci->form_validation->set_value('nama_lengkap'); ?>">
                        <span class="help-block"></span>
                    </div>

                    <div class="form-group">
                        <label for="">Alamat</label>
                        <input type="text" name="alamat" id="alamat" class="form-control"
                            value="<?php echo $ci->form_validation->set_value('alamat'); ?>">
                        <span class="help-block"></span>
                    </div>

                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                            value="<?php echo $ci->form_validation->set_value('email'); ?>">
                        <small class="text-primary">Email responden harus aktif dan email tidak boleh sama dengan email
                            yang lain.</small>
                        <span class="help-block"></span>
                    </div>

                    <div class="form-group">
                        <label for="">WhatsApp</label>
                        <input type="text" name="telepon" id="telepon" class="form-control"
                            value="<?php echo $ci->form_validation->set_value('telepon'); ?>">
                        <span class="help-block"></span>
                    </div>

                    <div class="form-group">
                        <label for="">Keterangan</label>
                        <input type="text" name="keterangan" id="keterangan" class="form-control"
                            value="<?php echo $ci->form_validation->set_value('keterangan'); ?>">
                        <span class="help-block"></span>
                    </div>

                </form>

            </div>
            <div class="modal-footer">

                <button type="button" id="btnSaveEdit" onclick="save_edit()"
                    class="btn btn-primary font-weight-bold shadow-lg">Simpan</button>
                <button type="button" class="btn btn-light-primary font-weight-bold shadow-lg"
                    data-dismiss="modal">Batal</button>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_userDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="exampleModalLabel">Caption</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" id="bodyModalDetail">
                <div align="center" id="loading_registration">
                    <img src="{{ base_url() }}assets/site/img/ajax-loader.gif" alt="">
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>
<script src="{{ TEMPLATE_BACKEND_PATH }}js/pages/crud/forms/widgets/select2.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.9.11/tinymce.min.js"></script>

<script>
var KTTinymce = function() {
    var demos = function() {
        tinymce.init({
            selector: '#template_email_prospek'
        });
        tinymce.init({
            selector: '#template_email_footer_prospek'
        });
    }
    return {
        init: function() {
            demos();
        }
    };
}();

// Initialization
jQuery(document).ready(function() {
    KTTinymce.init();
});
</script>

<script>
$(document).ready(function() {

    table = $('#table').DataTable({

        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "{{ $ajax_link }}",
            "type": "POST",
            "data": function(data) {}
        },

        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }, ],

    });

});


function reload_table() {
    table.ajax.reload(null, false);
}

function add_prospek() {

    $('#form')[0].reset();
    $('.form-control').removeClass('is-invalid');
    $('.help-block').empty();
    $('#modal_form_add').modal('show');
    $('.modal-title').text('Tambah Prospek');

}


function save_add() {
    $('#btnSave').text('Saving...');
    $('#btnSave').attr('disabled', true);

    $.ajax({
        url: "{{ $ajax_add }}",
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data) {

            if (data.status == true) {
                $('#modal_form_add').modal('hide');
                reload_table();

                // Swal.fire(
                //   'Sukses',
                //   'Data berhasil disimpan',
                //   'success'
                // )

                toastr.options = {
                    "closeButton": true,
                    "positionClass": "toast-top-center",
                    "progressBar": true,
                };
                toastr["success"]('Data berhasil disimpan');

            } else if (data.status == false) {

                $('#modal_form_add').modal('hide');
                reload_table();

                toastr["error"]('Data gagal disimpan');
            } else {

                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[name="' + data.inputerror[i] + '"]').next().html(data.error_string[i]);
                }
            }
            $('#btnSave').text('Simpan');
            $('#btnSave').attr('disabled', false);


        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
            $('#btnSave').text('Simpan');
            $('#btnSave').attr('disabled', false);

        }
    });
}


function edit_prospek(id) {
    $('#form_edit')[0].reset();
    $('.help-block').empty();
    $('#value_suborganisasi_edit').html('');

    $.ajax({
        url: "{{ $ajax_edit }}/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {

            $('[name="id"]').val(data.id);
            $('[name="nama_lengkap"]').val(data.nama_lengkap);
            $('[name="alamat"]').val(data.alamat);
            $('[name="email"]').val(data.email);
            $('[name="telepon"]').val(data.telepon);
            $('[name="keterangan"]').val(data.keterangan);


            $('#modal_form_edit').modal('show');
            $('.modal-title').text('Edit Prospek');

        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error get data from ajax');
        }
    });
}

function save_edit() {
    $('#btnSaveEdit').text('saving...');
    $('#btnSaveEdit').attr('disabled', true);

    $.ajax({
        url: "{{ $ajax_update }}",
        type: "POST",
        data: $('#form_edit').serialize(),
        dataType: "JSON",
        success: function(data) {

            if (data.status == true) {
                $('#modal_form_edit').modal('hide');
                reload_table();

                /*Swal.fire(
                  'Sukses',
                  'Data berhasil diupdate',
                  'success'
                )*/

                toastr.options = {
                    "closeButton": true,
                    "positionClass": "toast-top-center",
                    "progressBar": true,
                };

                toastr["success"]('Data berhasil diupdate');
            } else if (data.status == false) {

                $('#modal_form_edit').modal('hide');
                reload_table();
                toastr["error"]('Data gagal diupdate');

            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[name="' + data.inputerror[i] + '"]').next().html(data.error_string[i]);
                }
            }

            $('#btnSaveEdit').text('Simpan');
            $('#btnSaveEdit').attr('disabled', false);


        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
            $('#btnSaveEdit').text('Simpan');
            $('#btnSaveEdit').attr('disabled', false);

        }
    });
}


function delete_prospek(id) {

    Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Anda akan menghapus data ini ?",
        type: 'warning',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oke',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.value) {

            $.ajax({
                url: "{{ $ajax_delete }}" + id,
                type: "POST",
                dataType: "JSON",
                success: function(data) {

                    $('#modal_form').modal('hide');
                    reload_table();

                    toastr.options = {
                        "closeButton": true,
                        "positionClass": "toast-top-center",
                        "progressBar": true,
                    };
                    toastr["success"]('Data berhasil dihapus');

                    // Swal.fire(
                    //   'Deleted!',
                    //   'Data berhasil dihapus',
                    //   'success'
                    // )
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });


        }
    })
}

function showemaildetail(id) {
    $('#bodyModalDetail').html(
        "<div class='text-center'><img src='{{ base_url() }}assets/img/ajax/ajax-loader-big.gif'></div>");

    $.ajax({
        type: "post",
        url: "{{ $ajax_bagikan_email }}",
        data: "id=" + id,
        dataType: "text",
        success: function(response) {

            $('.modal-title').text('Bagikan Via Email');
            $('#bodyModalDetail').empty();
            $('#bodyModalDetail').append(response);
        }
    });
}

function showwadetail(id) {
    $('#bodyModalDetail').html(
        "<div class='text-center'><img src='{{ base_url() }}assets/img/ajax/ajax-loader-big.gif'></div>");

    $.ajax({
        type: "post",
        url: "{{ $ajax_bagikan_whatsapp }}",
        data: "id=" + id,
        dataType: "text",
        success: function(response) {

            $('.modal-title').text('Bagikan Via WhatsApp');
            $('#bodyModalDetail').empty();
            $('#bodyModalDetail').append(response);
        }
    });
}

$(document).ready(function() {
    let jumlah_prospek = <?= $jumlah_prospek; ?>;
    let is_question = <?= $profiles->is_question; ?>;

    if (is_question != 2) {

        Swal.fire({
            icon: 'warning',
            title: 'Informasi',
            html: '<div>Link survei belum dikonfirmasi, silahkan anda konfirmasi terlebih dahulu. <br><a style="text-decoration:none; color: blue;" href="<?= base_url(); ?><?= $ci->session->userdata('username'); ?>/<?= $ci->uri->segment(2) ?>/link-survey">Kembali ke link survei</a></div>',
            showConfirmButton: false,
            allowOutsideClick: false

        });

    } else {

        if (jumlah_prospek == 0) {
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: 'Anda belum menambahkan prospek. Anda bisa menambahkannya sekarang.',
                allowOutsideClick: false

            });
        }
    }

});

function empty_data() {
    Swal.fire({
        title: 'Informasi',
        text: "Apakah anda akan mengkosongkan data prospek ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: "{{ base_url() }}{{ $ci->session->userdata('username'); }}/{{ $ci->uri->segment(2) }}/data-prospek-survey/truncate",
                type: "POST",
                dataType: "JSON",
                success: function(data) {

                    reload_table();

                    toastr.options = {
                        "closeButton": true,
                        "positionClass": "toast-top-center",
                        "progressBar": true,
                    };

                    toastr["success"]('Data prospek berhasil dikosongkan');

                    document.getElementById("btn_empty_data").style.display = 'none';

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });

        }

    });
}
</script>

<script>
$('.form_template_email').submit(function(e) {

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolSimpan').attr('disabled', 'disabled');
            $('.tombolSimpan').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');
        },
        complete: function() {
            $('.tombolSimpan').removeAttr('disabled');
            $('.tombolSimpan').html('<i class="flaticon-edit-1 icon-md"></i> Update Template');
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

                toastr.options = {
                    "closeButton": true,
                    "positionClass": "toast-top-center",
                    "progressBar": true,
                };
                toastr["success"]('template email berhasil disimpan');
            }
        }
    })
    return false;
});
</script>

<script>
$('.form_template_footer_email').submit(function(e) {

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolSimpan').attr('disabled', 'disabled');
            $('.tombolSimpan').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');
        },
        complete: function() {
            $('.tombolSimpan').removeAttr('disabled');
            $('.tombolSimpan').html('<i class="flaticon-edit-1 icon-md"></i> Update Template');
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

                toastr.options = {
                    "closeButton": true,
                    "positionClass": "toast-top-center",
                    "progressBar": true,
                };
                toastr["success"]('template email berhasil disimpan');
            }
        }
    })
    return false;
});
</script>
@endsection
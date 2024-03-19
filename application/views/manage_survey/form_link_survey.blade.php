@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

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


                        <a class="btn btn-secondary font-weight-bold btn-sm"
                            href="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/data-prospek-survey' ?>"><i
                                class="fas fa-users text-dark"></i> Data Prospek</a>

                        @if ($profiles->is_question == 2)
                        <a class="btn btn-secondary btn-sm font-weight-bold"
                            href="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/link-survey/update-link' ?>"><i
                                class="fas fa-link"></i> Ubah Link Survei</a>
                        @endif



                    </div>
                </div>
            </div>




            @if ($profiles->is_question == 2)
            <div class="card" data-aos="fade-down">
                <!-- <div class="card-header font-weight-bold bg-light-primary">
                    Link Survey
                </div> -->
                <div class="card-body">

                    <div class="row">
                        <div class="col-xl-2"></div>
                        <div class="col-xl-8">
                            <div class="my-5">


                                <div class="text-center">
                                    <div class="mt-5 mb-10">
                                        Anda bisa menggunakan link survei untuk dibagikan kepada responden di bawah ini.
                                    </div>

                                    <div class='input-group'>
                                        <input type='text' class='form-control' id='kt_clipboard_1'
                                            value="{{ base_url() }}survei/{{ $ci->uri->segment(2) }}"
                                            placeholder='Type some value to copy' />
                                        <div class='input-group-append'>
                                            <a href='javascript:void(0)'
                                                class='btn btn-light-primary font-weight-bold shadow'
                                                data-clipboard='true' data-clipboard-target='#kt_clipboard_1'><i
                                                    class='la la-copy'></i> Copy Link</a>
                                        </div>
                                    </div>

                                    <div class="mt-10 mb-10">
                                        Atau gunakan tombol dibawah ini.
                                    </div>

                                    @php
                                    echo anchor(base_url().'survei/'.$ci->uri->segment(2), '<i class="fas fa-globe"></i>
                                    Menuju Link Survei', ['class' => 'btn btn-primary font-weight-bold btn-block
                                    shadow-lg', 'target' => '_blank']);
                                    @endphp


                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-5" data-aos="fade-down">
                <div class="card-body">
                    @php
                    $checked = ($profiles->is_publikasi_link_survei == 1) ? "checked" : "";
                    @endphp

                    <div class="row">
                        <div class="col-md-6">
                            <b>Publikasikan Link Survei ?</b>
                        </div>
                        <div class="col-md-6">

                            <span class="switch switch-sm">
                                <label>
                                    <input value="{{$profiles->is_publikasi_link_survei}}" type="checkbox"
                                        name="setting_value" class="toggle_dash" {{ $checked }} />
                                    <span></span>
                                </label>
                            </span>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card mt-5 mb-5" data-aos="fade-down">
                <div class="card-header font-weight-bold bg-light-danger">
                    Ubah Susunan dan Pertanyaan Survei
                </div>
                <div class="card-body">

                    <form class="form_submit" action="{{ $form_action }}" method="POST">
                        <div class="row">
                            <div class="col-xl-2"></div>
                            <div class="col-xl-8">
                                <div class="my-5">
                                    <h6 class="font-weight-bold mb-10 text-danger">
                                        Dengan menekan tombol konfirmasi dibawah ini berarti anda akan merubah susunan
                                        pertanyaan survei dan hasil survei sebelumnya akan dihapus serta tidak bisa
                                        dikembalikan kembali.
                                    </h6>

                                    <div class="form-group row">
                                        <label class="col-3">Konfirmasi perubahan pertanyaan untuk unsur berikut
                                            ini</label>
                                        <div class="col-9">
                                            
                                           

                                            @if($profiles->is_layanan_survei != 0)
                                            <div class="mb-5">
                                                <i class="fas fa-check-circle text-danger"></i> Jenis Pelayanan
                                            </div>
                                            @endif

                                            <div class="mb-5">
                                                <i class="fas fa-check-circle text-danger"></i> Profil Responden
                                            </div>

                                            <div class="mb-5">
                                                <i class="fas fa-check-circle text-danger"></i> Pertanyaan Unsur
                                            </div>

                                            @if(in_array(1, $atribut_pertanyaan))
                                            <div class="mb-5">
                                                <i class="fas fa-check-circle text-danger"></i> Pertanyaan Harapan
                                            </div>
                                            @endif

                                            @if(in_array(2, $atribut_pertanyaan))
                                            <div class="mb-5">
                                                <i class="fas fa-check-circle text-danger"></i> Pertanyaan Tambahan
                                            </div>
                                            @endif

                                            @if(in_array(3, $atribut_pertanyaan))
                                            <div class="mb-5">
                                                <i class="fas fa-check-circle text-danger"></i> Pertanyaan Kualitatif
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- <p>Dengan menekan tombol konfirmasi dibawah ini berarti anda akan merubah susunan
                                        pertanyaan survei.</p> -->
                                    <!-- <p class="text-danger">Jika anda mengkonfirmasi maka kuesioner terisi sebelumnya
                                        akan dihapus dan tidak bisa dikembalikan kembali.</p> -->
                                    <p class="font-weight-bold">Klik tombol konfirmasi jika anda setuju.</p>

                                    <input type="hidden" name="is_question" value="1">
                                    <button type="submit"
                                        onclick="return confirm('Apakah anda yakin ingin mengkonfirmasi link survei ?')"
                                        class="btn btn-light-secondary font-weight-bold shadow btn-block text-dark tombolSubmit"><i
                                            class="fas fa-info-circle text-danger"></i> Konfirmasi</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @else

            <div class="card mb-5" data-aos="fade-down">
                <div class="card-header font-weight-bold">
                    Status Pengisian Unsur Pertanyaan dan Pertanyaan
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-2"></div>
                        <div class="col-xl-8">
                            <div class="my-5">
                                <table class="table">

                                    @if($profiles->is_layanan_survei != 0)
                                    <tr>
                                        <th><a href="{{ base_url() }}{{ $ci->session->userdata('username') }}/{{ $ci->uri->segment(2) }}/layanan-survei"
                                                title="">Jenis Pelayanan</a></th>
                                        <td>
                                            @if ($layanan_survei == 0)
                                            <span class="badge badge-danger">Belum Diisi</span>
                                            @else
                                            <span class="badge badge-success">Oke</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif

                                    <tr>
                                        <th><a href="{{ base_url() }}{{ $ci->session->userdata('username') }}/{{ $ci->uri->segment(2) }}/profil-responden-survei"
                                                title="">Profil Responden</a></th>
                                        <td>
                                            @if ($profil_responden == 0)
                                            <span class="badge badge-danger">Belum Diisi</span>
                                            @else
                                            <span class="badge badge-success">Oke</span>
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <th><a href="{{ base_url() }}{{ $ci->session->userdata('username') }}/{{ $ci->uri->segment(2) }}/pertanyaan-unsur"
                                                title="">Pertanyaan Unsur</a></th>
                                        <td>
                                            @if ($pertanyaan_unsur == 0)
                                            <span class="badge badge-danger">Belum Diisi</span>
                                            @else
                                            <span class="badge badge-success">Oke</span>
                                            @endif
                                        </td>
                                    </tr>

                                    @if(in_array(1, $atribut_pertanyaan))
                                    <tr>
                                        <th><a href="{{ base_url() }}{{ $ci->session->userdata('username') }}/{{ $ci->uri->segment(2) }}/pertanyaan-harapan"
                                                title="">Pertanyaan Harapan</a></th>
                                        <td>
                                            @if ($pertanyaan_unsur == 0)
                                            <span class="badge badge-danger">Belum Diisi</span>
                                            @else
                                            <span class="badge badge-success">Oke</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif

                                    @if(in_array(2, $atribut_pertanyaan))
                                    <tr>
                                        <th><a href="{{ base_url() }}{{ $ci->session->userdata('username') }}/{{ $ci->uri->segment(2) }}/pertanyaan-terbuka"
                                                title="">Pertanyaan Tambahan</a></th>
                                        <td>
                                            @if ($pertanyaan_terbuka == 0)
                                            <span class="badge badge-danger">Belum Diisi</span>
                                            @else
                                            <span class="badge badge-success">Oke</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif


                                    @if(in_array(3, $atribut_pertanyaan))
                                    <tr>
                                        <th><a href="{{ base_url() }}{{ $ci->session->userdata('username') }}/{{ $ci->uri->segment(2) }}/pertanyaan-kualitatif"
                                                title="">Pertanyaan Kualitatif</a></th>
                                        <td>
                                            @if ($pertanyaan_kualitatif == 0)
                                            <span class="badge badge-danger">Belum Diisi</span>
                                            @else
                                            <span class="badge badge-success">Oke</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                </table>

                                <p>Link kuesioner akan ditampilkan ketika Pengisian Unsur Pertanyaan dan Pertanyaan
                                    sudah lengkap.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            @if ($profil_responden > 0 && $layanan_survei > 0 && $hasil_atribute)
            <div class="card" data-aos="fade-down">
                <div class="card-header font-weight-bold bg-light-success">
                    Link Survei
                </div>
                <div class="card-body">
                    <form class="form_submit" action="{{ $form_action }}" method="POST">
                        <div class="row">
                            <div class="col-xl-2"></div>
                            <div class="col-xl-8">
                                <div class="my-5">
                                    <h3 class="text-dark font-weight-bold mb-10">Konfirmasi Pengisian Soal dan
                                        Pertanyaan Survei</h3>
                                    <div class="form-group row">
                                        <label class="col-3">Konfirmasi</label>
                                        <div class="col-9">
                                            @if($profiles->is_layanan_survei != 0)
                                            <div class="mb-5">
                                                <i class="fas fa-check-circle text-success"></i> Jenis Pelayanan
                                            </div>
                                            @endif

                                            <div class="mb-5">
                                                <i class="fas fa-check-circle text-success"></i> Profil Responden
                                            </div>

                                            <div class="mb-5">
                                                <i class="fas fa-check-circle text-success"></i> Pertanyaan Unsur
                                            </div>

                                            @if(in_array(1, $atribut_pertanyaan))
                                            <div class="mb-5">
                                                <i class="fas fa-check-circle text-success"></i> Pertanyaan Harapan
                                            </div>
                                            @endif

                                            @if(in_array(2, $atribut_pertanyaan))
                                            <div class="mb-5">
                                                <i class="fas fa-check-circle text-success"></i> Pertanyaan Tambahan
                                            </div>
                                            @endif

                                            @if(in_array(3, $atribut_pertanyaan))
                                            <div class="mb-5">
                                                <i class="fas fa-check-circle text-success"></i> Pertanyaan Kualitatif
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <p>Dengan menekan tombol konfirmasi dibawah ini berarti anda sudah membuat
                                        pertanyaan survei yang telah diisi dengan benar dan siap dilakukan pengisian
                                        survei.<br>Link kuesioner akan ditampilkan ketika anda sudah menekan tombol
                                        konfirmasi.</p>
                                    <input type="hidden" name="is_question" value="2">
                                    <button type="submit"
                                        onclick="return confirm('Apakah anda yakin ingin mengkonfirmasi link survei ?')"
                                        class="btn btn-light-success font-weight-bold shadow btn-block tombolSubmit"
                                        onclick="submit_data()"><i class="fas fa-check-circle text-success"></i>
                                        Konfirmasi</button>
                                </div>


                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif
            @endif

        </div>
    </div>


</div>

@endsection

@section('javascript')

<script>
$('.toggle_dash').change(function() {

    var mode = $(this).prop('checked');
    var nilai_id = $(this).val();

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: "{{ base_url() }}{{ $ci->session->userdata('username') }}/{{ $ci->uri->segment(2) }}/update-publikasi-link-survei",
        data: {
            'mode': mode,
            'nilai_id': nilai_id
        },
        success: function(data) {
            var data = eval(data);
            message = data.message;
            success = data.success;

            toastr["success"](message);
            // window.setTimeout(function() {
            //     location.reload()
            // }, 1500);
        }
    });

});
</script>

<script>
"use strict";
var KTClipboardDemo = function() {

    var demos = function() {
        new ClipboardJS('[data-clipboard=true]').on('success', function(e) {
            e.clearSelection();
            toastr["success"]('Link berhasil dicopy, Silahkan paste di browser anda sekarang.');
        });
    }
    return {
        init: function() {
            demos();
        }
    };
}();

jQuery(document).ready(function() {
    KTClipboardDemo.init();
});
</script>

<script>
$(document).ready(function(e) {

    $('.form_submit').submit(function(e) {

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            beforeSend: function() {
                $('.tombolSubmit').attr('disabled', 'disabled');
                $('.tombolSubmit').html(
                    '<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

                Swal.fire({
                    title: 'Memproses data',
                    html: 'Mohon tunggu sebentar. Sistem sedang menyiapkan request anda.',
                    onOpen: () => {
                        swal.showLoading()
                    }
                });

            },
            complete: function() {
                $('.tombolSubmit').removeAttr('disabled');
                $('.tombolSubmit').html('Konfirmasi');
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

                    window.location.href =
                        "{{ base_url() }}{{ $ci->session->userdata('username') }}/{{ $ci->uri->segment(2) }}/link-survey";

                }
            }
        })
        return false;
    });



});
</script>
@endsection
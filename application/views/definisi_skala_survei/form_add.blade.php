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
            @include('include_backend/partials_backend/_message')
            <div class="card" data-aos="fade-down">
                <div class="card-header bg-secondary">
                    <h5>{{ $title }}</h5>
                </div>
                <div class="card-body">

                    <div class="alert alert-custom alert-notice alert-light-primary fade show mb-5" role="alert">
                        <div class="alert-icon"><i class="flaticon-warning"></i></div>
                        <div class="alert-text">Nilai Interval pada sistem ini menggunakan skala 100.</div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="ki ki-close"></i></span>
                            </button>
                        </div>
                    </div>

                    <form
                        action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/definisi-skala/add' ?>"
                        method="POST">

                        <span style="color: red; font-style: italic;"><?php echo validation_errors() ?></span>

                        <input name="id" value="1" hidden>

                        <div class="control-group after-add-more">
                            <div class="form-group row">
                                <div class="col-sm-11">
                                    <div class="card card-body" style="padding: 1rem;">

                                        <table class="table table-borderless" width="100%">
                                            <tr>
                                                <td width="20%">
                                                    <label class="font-weight-bold text-primary">Range Atas <b
                                                            class="text-danger">*</b></label>
                                                    <input type="number" class="form-control" value=""
                                                        placeholder="Misalkan (0)" name="range_atas[]" autofocus
                                                        required>
                                                </td>
                                                <td width="20%">
                                                    <label class="font-weight-bold text-primary">Range Bawah <b
                                                            class="text-danger">*</b></label>
                                                    <input type="number" class="form-control"
                                                        placeholder="Misalkan (20)" name="range_bawah[]" required>
                                                </td>
                                                <td width="20%">
                                                    <label class="font-weight-bold text-primary">Mutu <b
                                                            class="text-danger">*</b></label>
                                                    <input type="text" class="form-control"
                                                        placeholder="Masukkan Mutu Pelayanan..." name="mutu[]" required>
                                                </td>
                                                <td width="40%">
                                                    <label class="font-weight-bold text-primary">Kategori <b
                                                            class="text-danger">*</b></label>
                                                    <input type="text" class="form-control"
                                                        placeholder="Masukkan Kategori Pelayanan..." name="kategori[]"
                                                        required>
                                                </td>
                                            </tr>
                                        </table>

                                    </div>
                                </div>
                                <div class="input-group-addon col-sm-1">
                                    <button class="btn btn-success add-more" type="button">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- class hide membuat form disembunyikan  -->
                        <!-- hide adalah fungsi bootstrap 3, klo bootstrap 4 pake invisible  -->
                        <div class="copy" style="display: none;">
                            <div class="control-group row mb-7">
                                <div class="col-sm-11">
                                    <div class="card card-body" style="padding: 1rem;">

                                        <table class="table table-borderless" width="100%">
                                            <tr>
                                                <td width="20%">
                                                    <label class="font-weight-bold text-primary">Range Atas <b
                                                            class="text-danger">*</b></label>
                                                    <input type="number" class="form-control"
                                                        placeholder="Masukkan Batas Atas..." value=""
                                                        name="range_atas[]" autofocus>
                                                </td>
                                                <td width="20%">
                                                    <label class="font-weight-bold text-primary">Range Bawah <b
                                                            class="text-danger">*</b></label>
                                                    <input type="number" class="form-control"
                                                        placeholder="Masukkan Batas Atas..." name="range_bawah[]">
                                                </td>
                                                <td width="20%">
                                                    <label class="font-weight-bold text-primary">Mutu <b
                                                            class="text-danger">*</b></label>
                                                    <input type="text" class="form-control"
                                                        placeholder="Masukkan Mutu Pelayanan..." name="mutu[]">
                                                </td>
                                                <td width="40%">
                                                    <label class="font-weight-bold text-primary">Kategori <b
                                                            class="text-danger">*</b></label>
                                                    <input type="text" class="form-control"
                                                        placeholder="Masukkan Kategori Pelayanan..." name="kategori[]">
                                                </td>
                                            </tr>
                                        </table>

                                    </div>
                                </div>
                                <div class="input-group-addon col-sm-1">
                                    <button class="btn btn-danger remove" type="button">
                                        <i class="fas fa-trash"></i></button>
                                </div>

                            </div>
                        </div>

                        <br>

                        <br>
                        <div class="text-right">
                            @php
                            echo
                            anchor(base_url().$ci->session->userdata('username').'/'.$ci->uri->segment(2).'/definisi-skala',
                            'Batal', ['class' => 'btn btn-light-primary font-weight-bold'])
                            @endphp
                            <button type="submit" class="btn btn-primary font-weight-bold">Simpan</button>

                            <!-- onclick="return confirm('Membuat Draf Nilai Interval baru akan menghapus draf nilai interval yang lama. Apa anda yakin ingin melakukannya ?')" -->
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

</div>

@endsection

@section ('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {

    var maxGroup = 10;

    $(".add-more").click(function() {
        if ($('body').find('.after-add-more').length < maxGroup) {
            var html = '<div class="control-group after-add-more">' + $(".copy").html() +
                '</div>';
            $('body').find('.after-add-more:last').after(html);
        } else {
            alert('Maximum ' + maxGroup + ' groups are allowed.');
        }
    });

    // saat tombol remove dklik control group akan dihapus 
    $("body").on("click", ".remove", function() {
        $(this).parents(".control-group").remove();
    });

});
</script>

@endsection
@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<style>
    .border-menu {
        border-color: #304EC0 !important;
        background-color: #f3f3f3;
    }

    .card-menu {
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">

    <div class="col-md-12">

        <div class="card card-custom bgi-no-repeat gutter-b" style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/rhone-2.svg)" data-aos="fade-down">
            <div class="card-body d-flex align-items-center">
                <div>
                    <h3 class="text-white font-weight-bolder line-height-lg mb-5">
                        E-SERTIFIKAT KESELURUHAN
                    </h3>
                </div>
            </div>
        </div>



        @if($induk->nilai_index != '')

        <div class="card" data-aos="fade-down">

            <div class="card-body">

                <p class="mb-5">
                    Anda bisa menerbitkan sertifikat SKM dengan melengkapi form dibawah ini. Sertifikat yang
                    diterbitkan dilengkapi dengan QrCode yang bisa dilakukan validasi kebenaran datanya. Desain
                    sertifikat akan selalu diupdate oleh administrator.
                </p>

                <form method="get" action="<?php echo base_url() . 'e-sertifikat-keseluruhan/cetak'  ?>" target="_blank">

                    <h4 class="mb-5"><u>Desain Sertifikat</u></h4>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Pilih Desain Sertifikat <span style="color: red;">*</span></label>
                        <!-- <input type="text" name="model_sertifikat"> -->
                        <div class="col-sm-9">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>
                                        <input type="radio" name="model_sertifikat" value="desain-1.jpg" required="required">
                                        <div class="card card-menu">
                                            <div class="card-body">

                                                <div class="text-center">
                                                    <img src="{{ base_url() }}assets/files/sertifikat/desain-1.jpg" alt="" width="150px;">
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label>
                                        <input type="radio" name="model_sertifikat" value="desain-2.jpg" required="required">
                                        <div class="card card-menu">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <img src="{{ base_url() }}assets/files/sertifikat/desain-2.jpg" alt="" width="150px;">
                                                </div>

                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label>
                                        <input type="radio" name="model_sertifikat" value="desain-3.jpg" required="required">
                                        <div class="card card-menu">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <img src="{{ base_url() }}assets/files/sertifikat/desain-3.jpg" alt="" width="150px;">
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>


                    <hr>
                    <h4 class="mb-5"><u>Penandatangan Sertifikat</u></h4>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama lengkap beserta title yang menandatangani
                            sertifikat <span style="color: red;">*</span></label>
                        <!-- <input type="text" name="model_sertifikat"> -->
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nama" placeholder="" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jabatan <span style="color: red;">*</span></label>
                        <!-- <input type="text" name="model_sertifikat"> -->
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="jabatan" placeholder="" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Pilih Periode Survei <span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-control" name="periode" required>
                                <option value="">Please Select</option>
                                <option value="Bulan Januari">Bulan Januari</option>
                                <option value="Bulan Februari">Bulan Februari</option>
                                <option value="Bulan Maret">Bulan Maret</option>
                                <option value="Bulan April">Bulan April</option>
                                <option value="Bulan Mei">Bulan Mei</option>
                                <option value="Bulan Juni">Bulan Juni</option>
                                <option value="Bulan Juli">Bulan Juli</option>
                                <option value="Bulan Agustus">Bulan Agustus</option>
                                <option value="Bulan September">Bulan September</option>
                                <option value="Bulan Oktober">Bulan Oktober</option>
                                <option value="Bulan November">Bulan November</option>
                                <option value="Bulan Desember">Bulan Desember</option>
                                <option value="Triwulan I">Triwulan I</option>
                                <option value="Triwulan II">Triwulan II</option>
                                <option value="Triwulan III">Triwulan III</option>
                                <option value="Triwulan IV">Triwulan IV</option>
                                <option value="Semester I">Semester I</option>
                                <option value="Semester II">Semester II</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tahun <span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" name="tahun" placeholder="" required>
                        </div>
                    </div>



                    <div class="text-right">
                        <button type="submit" class="btn btn-primary font-weight-bold" target="_blank"><i class="fas fa-print"></i>Generate Serifikat</button>
                    </div>

                </form>

            </div>
        </div>

        @else 
        
        <div class="card card-body">
                <div class="text-danger text-center"><i>Belum ada data responden yang sesuai.</i></div>
            </div>

       
        @endif

    </div>


</div>
@endsection

@section('javascript')
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

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
    $('.card-menu').hover(
        function() {
            $(this).addClass('border-menu shadow')
        },
        function() {
            $(this).removeClass('border-menu shadow')
        }
    )
</script>

<script>
    $(document).ready(function() {

        $("#profil_responden").select2({
            placeholder: "   Please Select",
            allowClear: true
        });

    });
</script>

@endsection
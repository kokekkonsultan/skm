@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header bg-secondary font-weight-bold">
            Pengaturan Email
        </div>
        <div class="card-body">


            <div class="" id="kt_blockui_content">
                <hr>
                <form action="{{ base_url() }}pengaturan/update-email" class="form_email">

                    <label for="" class="font-weight-bold">Akun Email</label>
                    <input type="email" name="email_akun" class="form-control" value="{{ $web_settings->email_akun }}">

                    <label for="" class="font-weight-bold">Email Pengirim</label>
                    <input type="email" name="email_pengirim" class="form-control" value="{{ $web_settings->email_pengirim }}">

                    <label for="" class="font-weight-bold">Username</label>
                    <input type="email" name="email_username" class="form-control" value="{{ $web_settings->email_username }}">

                    <label for="" class="font-weight-bold">Password</label>
                    <input type="password" name="email_password" class="form-control" value="">

                    <label for="" class="font-weight-bold">Host</label>
                    <input type="text" name="email_host" class="form-control" value="{{ $web_settings->email_host }}">

                    <label for="" class="font-weight-bold">Port</label>
                    <input type="number" name="email_port" class="form-control" value="{{ $web_settings->email_port }}">

                    <label for="" class="font-weight-bold">Email CC</label>
                    <input type="email" name="email_cc" class="form-control" value="{{ $web_settings->email_cc }}">

                    <label for="" class="font-weight-bold">Email BCC</label>
                    <input type="email" name="email_bcc" class="form-control" value="{{ $web_settings->email_bcc }}">

                    <div class="mt-5 text-right">
                        <button type="submit" class="btn btn-light-primary font-weight-bold tombolsimpanEmail shadow">Simpan</button>
                    </div>

                </form>

                <br>
                <hr><br>


                <h3>Test Email</h3>
                <small>Digunakan untuk uji coba pengaturan email. Sebelum menggunakan test email, pastikan pengaturan akun email diatas sudah benar !</small>
                <br>
                <form action="{{ base_url() }}pengaturan/test-email" class="form_test_email" method="POST">

                    <label for="" class="font-weight-bold">Kirimkan Email Ke *</label>
                    <input type="email" name="email_akun_test" class="form-control" value="" required="required">

                    <label for="" class="font-weight-bold">Isi Email *</label>
                    <textarea name="isi_email" class="form-control" required="required"></textarea>

                    <div class="mt-5 text-right">
                        <button type="submit" class="btn btn-light-primary font-weight-bold tombolEmailTest shadow">Kirim</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>

@endsection

@section('javascript')
<script>
    $(document).ready(function(e) {
        $('.form_email').submit(function(e) {

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                dataType: 'json',
                data: $(this).serialize(),
                cache: false,
                beforeSend: function() {
                    $('.tombolsimpanEmail').attr('disabled', 'disabled');
                    $('.tombolsimpanEmail').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

                    KTApp.block('#kt_blockui_content', {
                        overlayColor: '#000000',
                        state: 'primary',
                        message: 'Processing...'
                    });

                    setTimeout(function() {
                        KTApp.unblock('#kt_blockui_content');
                    }, 1000);

                },
                complete: function() {
                    $('.tombolsimpanEmail').removeAttr('disabled');
                    $('.tombolsimpanEmail').html('Simpan');
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


                    }
                }
            })
            return false;
        });

        $('.form_test_email').submit(function(e) {

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                dataType: 'json',
                data: $(this).serialize(),
                cache: false,
                beforeSend: function() {
                    $('.tombolEmailTest').attr('disabled', 'disabled');
                    $('.tombolEmailTest').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');


                    KTApp.block('#kt_blockui_content', {
                        overlayColor: '#000000',
                        state: 'primary',
                        message: 'Processing...'
                    });

                    setTimeout(function() {
                        KTApp.unblock('#kt_blockui_content');
                    }, 5000);
                },
                complete: function() {
                    $('.tombolEmailTest').removeAttr('disabled');
                    $('.tombolEmailTest').html('Kirim');

                    $('[name="email_akun_test"]').val('');
                    $('[name="isi_email"]').val('');
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
                        toastr["success"]('Email berhasil dikirim');
                    }
                }
            })
            return false;
        });

    });
</script>
@endsection
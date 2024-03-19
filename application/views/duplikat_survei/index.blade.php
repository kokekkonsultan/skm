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

    <div class="card" data-aos="fade-down">
        <div class="card-body">

            <div class="mb-5">
                <h3 class="text-primary">{{strtoupper($title)}}</h3>
                <p>Halaman ini digunakan untuk menduplikat survei ke akun anak.</p>
            </div>

            <hr>

            <form class="form-default" action="{{base_url() . 'duplikat-survei/proses'}}" method="POST">

                <div class="row mt-5">
                    <label class="col-sm-2 col-form-label
                        font-weight-bold">Dari Survei <span style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <select id="id_survei" name="id_survei[]" class="form-control" multiple="multiple" required>

                            @php
                            $user_id = $ci->session->userdata('user_id');
                            @endphp
                            @foreach($ci->db->get_where('users', ['id_parent_induk' => $user_id])->result() as $row)
                            <optgroup label="{{$row->first_name . ' ' . $row->last_name}}">
                                @foreach($ci->db->get_where('manage_survey', ['id_user' => $row->id])->result() as
                                $value)
                                <option value="{{$value->id}}">{{$value->survey_name}}</option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>

                <br>


                <div class="row mt-5">
                    <label class="col-sm-2 col-form-label
                        font-weight-bold">Tujuan <span style="color: red;">*</span></label>
                    <div class="col-sm-10">

                        <div class="checkbox-list">

                            @foreach($ci->db->get_where('users', ['id_parent_induk' => $user_id])->result() as $row)
                            <label class="checkbox">
                                <input type="checkbox" name="id_user[]" value="{{$row->id}}">
                                <span></span> {{$row->first_name . ' ' . $row->last_name}}
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <br>

                <hr>

                <div class="text-right mt-5">
                    <a class="btn btn-secondary font-weight-bold tombolCancel" href="{{base_url() . 'dashboard'}}">Kembali</a>
                    <button class="btn btn-primary font-weight-bold tombolSimpan" type="submit">Simpan</button>
                </div>

            </form>

           


        </div>
    </div>
</div>

@endsection

@section('javascript')
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


<script>
$(document).ready(function() {

    $("#id_survei").select2({
        placeholder: "   Please Select",
        allowClear: true
    });

});
</script>


<script>
$('.form-default').submit(function(e) {

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolSimpan').attr('disabled', 'disabled');
            $('.tombolSimpan').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');
            $('.tombolCancel').attr('disabled', 'disabled');

            Swal.fire({
                    title: 'Memproses data',
                    html: 'Mohon tunggu sebentar. Sistem sedang menyiapkan request anda.',
                    allowOutsideClick: false,
                    onOpen: () => {
                        swal.showLoading()
                    }
                });

        },
        complete: function() {
            $('.tombolSimpan').removeAttr('disabled');
            $('.tombolSimpan').html('Simpan');
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
                Swal.fire(
                    'Informasi',
                    'Berhasil memproses data',
                    'success'
                );
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
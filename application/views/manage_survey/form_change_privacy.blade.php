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
            <div class="card mb-5" data-aos="fade-down">

                <div class="card-body">

                    @php
                    $checked = ($profiles->is_privacy == 1) ? "checked" : "";
                    @endphp

                    <div class="row">
                        <div class="col-md-6">
                            Aktifkan status survei ke Public ?
                        </div>
                        <div class="col-md-6">

                            <span class="switch switch-sm">
                                <label>
                                    <input value="{{ $profiles->is_privacy }}" type="checkbox" name="setting_value"
                                        class="toggle_dash" {{ $checked }} />
                                    <span></span>
                                </label>
                            </span>
                        </div>
                    </div>



                </div>
            </div>






        </div>
    </div>

    <div class="row justify-content-md-center">
        <div class="col col-lg-8">
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
        url: "{{ base_url() }}{{ $ci->session->userdata('username') }}/{{ $ci->uri->segment(2) }}/do/change-privacy/update",
        data: {
            'mode': mode,
            'nilai_id': nilai_id
        },
        success: function(data) {
            var data = eval(data);
            message = data.message;
            success = data.success;

            toastr["success"](message);


            let timerInterval
            Swal.fire({
                title: 'Pengaturan berhasil disimpan',
                html: 'Refresh akan dimulai beberapa saat.',
                confirmButtonText: 'Ya, saya mengerti',
                // timer: 2000,
                // onBeforeOpen: () => {

                // },
                // onClose: () => {
                //     clearInterval(timerInterval)
                // }
            }).then((result) => {
                // if (
                //     result.dismiss === Swal.DismissReason.timer
                // ) {
                //     location.reload();
                // }
                window.setTimeout(function() {
                    location.reload()
                }, 1500);
            });



        }
    });

});
</script>
@endsection
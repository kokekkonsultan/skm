@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container">
    <div class="row justify-content-md-center">
        <div class="col col-lg-8 mt-5">

            {!! form_open(uri_string()); !!}

            <!-- <div class="card card-body mb-5">
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label font-weight-bold">Dari Reseller ? <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <div class="radio-list mb-5">
                            <label class="radio"><input type="radio" name="cek_res" id="2" value="2" class="tamplate"
                                   <?php echo $user->is_reseller == '' ? 'checked' : '' ?>><span></span>&nbsp
                                Tidak</label>
                                
                            <label class="radio"><input type="radio" name="cek_res" id="1" value="1" class="tamplate"
                                   <?php echo $user->is_reseller != '' ? 'checked' : '' ?>
                                    ><span></span>&nbsp
                                Ya</label>
                        </div>

                        @php
                        $reseller = $ci->db->query('SELECT * FROM users
                        JOIN users_groups ON users.id = users_groups.user_id
                        WHERE group_id = 4');
                        @endphp
                        <select class="form-control" name="is_reseller" id="is_reseller"
                            <?php if($user->is_reseller == '') { echo 'style="display: none;"'; } else { 'required'; } ?>>
                            <option value="">Please Select</option>

                            @foreach($reseller->result() as $row)
                            <option value="{{$row->user_id}}" <?php if ($row->user_id == $user->is_reseller) {
                                    echo 'selected';
                                } ?>>{{$row->first_name}} {{$row->last_name}}</option>
                            @endforeach
                        </select>

                        <?//php if($user->is_reseller == '') { echo 'style="display: none;"'; } else { ''; } ?>

                    </div>
                </div>
            </div> -->


            <div class="card">
                <div class="card-header font-weight-bold">
                    Data PIC Klien
                </div>
                <div class="card-body">

                    <div id="infoMessage">{!! $message; !!}</div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama Depan <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($first_name); !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama Belakang <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($last_name); !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Organisasi <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($company); !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">HP <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($phone); !!}
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Password <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($password); !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Ulangi Password <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($password_confirm); !!}
                        </div>
                    </div>




                </div>
            </div>

            <div class="card mt-5">
                <div class="card-header font-weight-bold">
                    Survei
                </div>
                <div class="card-body">

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Klasifikasi Survei <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_dropdown($id_klasifikasi_survei); !!}
                        </div>
                    </div>

                </div>
            </div>

            <div class="card mt-5">
                <div class="card-header font-weight-bold">
                    Berlangganan
                </div>
                <div class="card-body">

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Paket Langganan <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_dropdown($id_paket); !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Metode Pembayaran <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_dropdown($id_metode_pembayaran); !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal Mulai Berlangganan <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($tanggal_mulai); !!}
                        </div>
                    </div>

                    {!! form_hidden('id', $user->id); !!}
                    {!! form_hidden($csrf) !!}

                </div>
            </div>

            <div class="text-right mt-5 mb-5">
                {!! anchor(base_url().'pengguna-klien', 'Cancel', ['class' => 'btn btn-light-primary font-weight-bold
                shadow-lg']) !!}
                {!! form_submit('submit', lang('edit_user_submit_btn'), ['class' => 'btn btn-primary font-weight-bold
                shadow-lg']); !!}
            </div>


            <div class="mt-5">
                <a class="btn btn-danger font-weight-bold" href="javascript:void(0)" title="Hapus"
                    onclick="delete_data(<?php echo $ci->uri->segment(3) ?>)">Hapus Pengguna Ini</a>
            </div>
            {!! form_close(); !!}

        </div>
    </div>


</div>

@endsection

@section('javascript')
<script>
function delete_data(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "Apakah anda akan menghapus pengguna ? Data tidak dapat dikembalikan",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oke, Saya mengerti!'
    }).then((result) => {

        if (result.value) {

            $.ajax({
                url: "{{ base_url() }}auth/delete-user/" + id,
                type: "POST",
                dataType: "JSON",
                beforeSend: function() {

                    Swal.fire({
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        title: "Mohon tunggu",
                        text: "Sistem sedang melakukan request anda.",
                        onOpen: function() {
                            Swal.showLoading()
                        }
                    })

                },

                success: function(data) {
                    if (data.status) {

                        Swal.fire(
                            'Deleted!',
                            'Data anda berhasil dihapus.',
                            'success'
                        );

                        setTimeout(function() {
                            window.location.href = ('{{ base_url() }}pengguna-klien');
                        }, 2000);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }

            });

        }
    });

}
</script>

<script type="text/javascript">
$(function() {
    $(":radio.tamplate").click(function() {
        $("#is_reseller").hide()
        if ($(this).val() == "1") {
            $("#is_reseller").show().prop('required', true);
        } else {
            $("#is_reseller").removeAttr('required').hidden();
        }
    });
});
</script>
@endsection
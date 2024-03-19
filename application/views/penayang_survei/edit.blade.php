@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')
<div class="container">
    <div class="card card-custom mb-5" data-aos="fade-down">
        <div class="card-header bg-secondary font-weight-bold">
            <div class="card-title">
                {{$title}}
            </div>
        </div>
        <div class="card-body">

            <form
                action="<?php echo base_url() . $ci->session->userdata('username') . '/penayang-survei/edit/' . $ci->uri->segment(4) ?>"
                method="POST" enctype="multipart/form-data">

                <span class="text-danger">{!! validation_errors() !!}</span>


                <div class="form-group row">
                    <label class="col-sm-2 col-form-label font-weight-bold">Nama Label <span
                            style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        {!! form_input($nama_label) !!}
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 col-form-label font-weight-bold">Image Banner <span
                            style="color: red;">*</span></label>
                    <div class="col-sm-10">

                        <span class="text-warning font weight-bold"><b>Gambar sebelumnya</b></span><br>
                        <img src="{{ base_url() }}assets/klien/benner_penayang/{{ $penayang_survei->img_benner }}"
                            alt="" width=100%">
                        <br>
                        <br>


                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="benner" id="profil">
                            <label class="custom-file-label" for="validatedCustomFile">Choose
                                file...</label>
                        </div>
                        <br>
                        <small class="text-danger">* Format file harus jpg/png.<br>* Ukuran max
                            file adalah 10MB.</small>
                    </div>
                </div>



                <div class="form-group row">
                    <label class="col-sm-2 col-form-label font-weight-bold">Kata Pembuka <span
                            style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        {!! form_textarea($kata_pembuka) !!}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label font-weight-bold">Pilih List Survei <span
                            style="color: red;">*</span></label>
                    <div class="col-sm-10">

                        @foreach($manage_survey->result() as $row)
                        <label class=""><input type="checkbox" name="list_survei" value="{{$row->id}}" class="child"
                                checked disabled>&nbsp{{$row->survey_name}}</label>
                        <br>
                        @endforeach
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 col-form-label font-weight-bold">Kata Penutup</label>
                    <div class="col-sm-10">
                        {!! form_textarea($kata_penutup) !!}
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 col-form-label font-weight-bold">Link Penayang <span
                            style="color: red;">*</span></label>
                    <div class="input-group col-sm-10">
                        <div class="input-group-prepend"><span
                                class="input-group-text"><?php echo base_url() . 'survei-list/' ?></span></div>
                        {!! form_input($link_penayang) !!}
                    </div>
                </div>

                <br>


                <div class="form-group row">
                    <label class="col-sm-2 col-form-label font-weight-bold">Jenis Penayangan <span
                            style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <div>
                            <label><input type="radio" name="jenis_penayang" id="default" value="1" class="customisasi"
                                    <?php echo $penayang_survei->jenis_penayang == '1' ? 'checked' : '' ?>>&nbsp
                                Card</label>
                        </div>
                        <hr>
                        <div>
                            <label>
                                <input type="radio" name="jenis_penayang" id="custom" value="2" class="customisasi"
                                    <?php echo $penayang_survei->jenis_penayang == '2' ? 'checked' : '' ?>>&nbsp
                                List</label>
                        </div>

                    </div>
                    </label>
                </div>

                <br>

                <table width="100%" class="mt-5">
                    <tr>
                        <td class="text-left">
                            <a class="btn btn-danger font-weight-bold" href="javascript:void(0)"
                                title="Hapus <?php echo $penayang_survei->nama_label ?>"
                                onclick="delete_data('<?php echo $penayang_survei->id ?>')"><i class="fa fa-trash"></i>
                                Delete Penayang Survei</a>
                        </td>
                        <td class="text-right">
                            <a class="btn btn-secondary font-weight-bold shadow"
                                href="<?php echo base_url() . $ci->session->userdata('username') . '/penayang-survei' ?>">Batal</a>
                            <button type="submit" class="btn btn-primary font-weight-bold shadow">Simpan</button>
                        </td>
                    </tr>
                </table>

            </form>




        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
<script>
ClassicEditor
    .create(document.querySelector('#kata_pembuka'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
    });

ClassicEditor
    .create(document.querySelector('#kata_penutup'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
    });



function delete_data() {
    if (confirm('Are you sure delete this data?')) {
        $.ajax({
            url: "<?php echo base_url() . $ci->session->userdata('username') . '/penayang-survei/delete/' . $ci->uri->segment(4) ?>",
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status) {

                    Swal.fire(
                        'Informasi',
                        'Berhasil menghapus data',
                        'success'
                    );
                    window.location.href =
                        "<?php echo base_url() . $ci->session->userdata('username') . '/penayang-survei/' ?>";
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
                window.location.href =
                    "<?php echo base_url() . $ci->session->userdata('username') . '/penayang-survei/' ?>";
            }
        });

    }
}
</script>
@endsection
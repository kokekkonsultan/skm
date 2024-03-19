@extends('include_backend/_template')

@php
$ci = get_instance();
@endphp

@section('style')
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

<style>
.sticky_button_edit {
    position: -webkit-sticky;
    position: sticky;
    top: 30%;
}


.pic-holder {
    text-align: center;
    position: relative;
    height: 100%;
    width: 100%;
    overflow: hidden;
    /* display: flex; */
    justify-content: center;
    align-items: center;
}


.pic-holder .pic {
    height: 100%;
    width: 100%;
    -o-object-fit: cover;
    object-fit: cover;
    -o-object-position: center;
    object-position: center;
}

.pic-holder .upload-file-block,
.pic-holder .upload-loader {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    background-color: rgba(90, 92, 105, 0.7);
    color: #f8f9fc;
    font-size: 12px;
    font-weight: 600;
    opacity: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.pic-holder .upload-file-block {
    cursor: pointer;
}

.pic-holder:hover .upload-file-block,
.uploadProfileInput:focus~.upload-file-block {
    opacity: 1;
}

.pic-holder.uploadInProgress .upload-file-block {
    display: none;
}

.pic-holder.uploadInProgress .upload-loader {
    opacity: 1;
}

/* Snackbar css */
.snackbar {
    visibility: hidden;
    min-width: 250px;
    background-color: #333;
    color: #fff;
    text-align: center;
    border-radius: 2px;
    padding: 16px;
    position: fixed;
    z-index: 1;
    left: 50%;
    bottom: 30px;
    font-size: 14px;
    transform: translateX(-50%);
}

.snackbar.show {
    visibility: visible;
    -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
    animation: fadein 0.5s, fadeout 0.5s 2.5s;
}
</style>

@endsection

@section('content')


<div class="container mt-5 mb-5" style="font-family: nunito;">
    <div class="text-center" data-aos="fade-up">
        <div id="progressbar" class="mb-5">
            <li id="account"><strong>Data Responden</strong></li>
            <li id="personal"><strong>Pertanyaan Survei</strong></li>
            @if($status_saran == 1)
            <li id="payment"><strong>Saran</strong></li>
            @endif
            <li id="confirm"><strong>Konfirmasi</strong></li>
            <li id="completed"><strong>Completed</strong></li>
        </div>
    </div>
    <br>
    <br>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <table class="">
                <tr>
                    <td>
                        <div class="card shadow" data-aos="fade-up">

                            <!-- @if($manage_survey->img_benner == '')
                            <img class="card-img-top" src="{{ base_url() }}assets/img/site/page/banner-survey.jpg"
                                alt="new image" />
                            @else
                            <img class="card-img-top shadow"
                                src="{{ base_url() }}assets/klien/benner_survei/{{$manage_survey->img_benner}}"
                                alt="new image">
                            @endif -->

                            <div class="pic-holder">

                                @if($manage_survey->img_benner == '')
                                <img id="profilePic" class="pic"
                                    src="{{ base_url() }}assets/img/site/page/banner-survey.jpg">
                                @else
                                <img id="profilePic" class="pic"
                                    src="{{ base_url() }}assets/klien/benner_survei/{{$manage_survey->img_benner}}">
                                @endif

                                <Input class="uploadProfileInput" type="file" name="profile_pic" id="newProfilePhoto"
                                    accept="image/*" style="opacity: 0;" />
                                <label for="newProfilePhoto" class="upload-file-block">
                                    <div class="text-center">
                                        <div class="mb-2">
                                            <i class="fa fa-camera fa-2x"></i>
                                        </div>
                                        <div class="text-uppercase">
                                            Ubah <br /> Benner Survei
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div class="card-body">
                                <div style="font-size: 14px;">
                                    @php
                                    $slug = $ci->uri->segment(2);

                                    $data_user = $ci->db->query("SELECT *
                                    FROM manage_survey
                                    JOIN users ON manage_survey.id_user = users.id
                                    WHERE slug = '$slug'")->row();
                                    @endphp

                                    {!! $data_user->deskripsi_opening_survey !!}
                                </div>
                                <br><br>
                                {!! anchor(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2)
                                .
                                '/form-survei/data-responden', 'IKUT SURVEI',
                                ['class' => 'btn btn-warning btn-block font-weight-bold shadow']) !!}

                            </div>
                        </div>
                    </td>
                    <td valign="top">

                        <div class="col-sm-1 sticky_button_edit">

                            <div class="btn-group-vertical mr-2" role="group" aria-label="First group"
                                data-aos="fade-up" style="box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.19);">

                                <button type="button" class="btn btn-white" data-toggle="modal"
                                    data-target="#deskripsi"><span data-toggle="tooltip" data-placement="right"
                                        title="Ubah Deskripsi"><i class="fa fa-edit"></i></span>
                                </button>

                                <a type="button" class="btn btn-white"
                                    href="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/preview-form-survei/opening' ?>"
                                    target="_blank"><span data-toggle="tooltip" data-placement="right"
                                        title="Lihat Tampilan Form Survei"><i class="fa fa-eye"></i></span></a>

                            </div>
                        </div>

                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="deskripsi" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border border-warning">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="exampleModalLabel">Edit Deskripsi</h5>
            </div>
            <div class="modal-body">
                <form
                    action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/form-survei/update-display' ?>"
                    class="form_pembuka">

                    <div class="form-group">
                        <textarea name="deskripsi" id="editor" value="" class="form-control"
                            required> <?php echo $manage_survey->deskripsi_opening_survey ?></textarea>
                    </div>

                    <div class="text-right">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm font-weight-bold tombolSimpanPembuka">Update
                            Deskripsi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')

<script>
$(document).on("change", ".uploadProfileInput", function() {
    var triggerInput = this;
    var currentImg = $(this).closest(".pic-holder").find(".pic").attr("src");
    var holder = $(this).closest(".pic-holder");
    var wrapper = $(this).closest(".profile-pic-wrapper");
    $(wrapper).find('[role="alert"]').remove();
    triggerInput.blur();
    var files = !!this.files ? this.files : [];
    if (!files.length || !window.FileReader) {
        return;
    }
    if (/^image/.test(files[0].type)) {
        // only image file
        var reader = new FileReader(); // instance of the FileReader
        reader.readAsDataURL(files[0]); // read the local file

        var formdata = new FormData();
        formdata.append('file', files[0]);

        reader.onloadend = function() {
            $(holder).addClass("uploadInProgress");
            $(holder).find(".pic").attr("src", this.result);
            $(holder).append(
                '<div class="upload-loader"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>'
            );

            $.ajax({
                method: 'POST',
                contentType: false,
                cache: false,
                processData: false,
                data: formdata,
                dataType: 'json',
                url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/form-survei/do-uploud' ?>",
            });

            // Dummy timeout; call API or AJAX below
            setTimeout(() => {
                $(holder).removeClass("uploadInProgress");
                $(holder).find(".upload-loader").remove();
                // If upload successful
                if (Math.random() < 0.9) {
                    toastr["success"]('Data berhasil disimpan');
                    window.setTimeout(function() {
                        location.reload()
                    }, 1500);

                    // Clear input after upload
                    $(triggerInput).val("");
                } else {
                    $(holder).find(".pic").attr("src", currentImg);
                    toastr["danger"](
                        'here is an error while uploading! Please try again later.');

                    // Clear input after upload
                    $(triggerInput).val("");
                }
            }, 1500);
        };
    } else {
        toastr["danger"]('Please choose the valid image.');
    }
});
</script>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>


<script>
ClassicEditor
    .create(document.querySelector('#editor'))
    .then(editor => {
        console.log(editor1);
    })
    .catch(error => {
        console.error(error);
    });
</script>

<script>
$('.form_pembuka').submit(function(e) {

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolSimpanPembuka').attr('disabled', 'disabled');
            $('.tombolSimpanPembuka').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

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
            $('.tombolSimpanPembuka').removeAttr('disabled');
            $('.tombolSimpanPembuka').html('Update Deskripsi');
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
                }, 1500);
            }
        }
    })
    return false;
});
</script>
@endsection
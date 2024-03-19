@php
$ci = get_instance();
$ci->load->helper('form');
@endphp

<form
    action="{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/form-survei/edit-pertanyaan-kualitatif/'.$ci->uri->segment(5)}}"
    method="POST" class="form_default">

    <div class="form-group row">
        <label class="col-sm-3 col-form-label
                        font-weight-bold">Isi Pertanyaan <span style="color: red;">*</span></label>
        <div class="col-sm-9">
            <textarea class="form-control" type="text" name="isi_pertanyaan" id="isi_pertanyaan_1"
                placeholder="Isikan Pertanyaan Kualitatif ..."><?php echo $kualitatif->isi_pertanyaan ?></textarea>
        </div>
    </div>
    <br>

    <div class="form-group row">
        <label class="col-sm-3 col-form-label
                        font-weight-bold">Status Pertanyaan <span style="color: red;">*</span></label>
        <div class="col-sm-9">
            <select class="form-control" id="is_active" name="is_active" value="<?php echo set_value('is_active'); ?>">
                <option>Please Select</option>
                <option value='1' <?php echo $kualitatif->is_active == 1 ? 'selected' : '' ?>>Aktif</option>
                <option value='2' <?php echo $kualitatif->is_active == 2 ? 'selected' : '' ?>>Tidak Aktif</option>
            </select>
        </div>
    </div>



    <div class="text-right mb-5">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary tombolDefault">Simpan</button>
    </div>
</form>

<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
<script>
ClassicEditor
    .create(document.querySelector('#isi_pertanyaan_1'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
    });
</script>

<script>
$('.form_default').submit(function(e) {

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolDefault').attr('disabled', 'disabled');
            $('.tombolDefault').html(
                '<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

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
            $('.tombolDefault').removeAttr('disabled');
            $('.tombolDefault').html('Simpan');
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
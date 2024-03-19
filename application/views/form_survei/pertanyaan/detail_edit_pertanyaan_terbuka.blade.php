@php
$ci = get_instance();
$ci->load->helper('form');
@endphp

<form
    action="{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/form-survei/edit-pertanyaan-tambahan/'.$ci->uri->segment(5)}}"
    method="POST" class="form_default">

    <div class="form-group row">
        @php
        echo form_label('Pertanyaan Tambahan <span style="color:red;">*</span>', '', ['class' =>
        'col-sm-3 col-form-label
        font-weight-bold']);
        @endphp
        <div class="col-sm-9">
            <div class="input-group">
                <div class="input-group-prepend"><span
                        class="input-group-text"><?php echo $current->nomor_pertanyaan_terbuka ?></span>
                </div>
                @php
                echo form_input($nama_pertanyaan_terbuka);
                @endphp
            </div>
        </div>
    </div>


    <div class="form-group row">
        <label class="col-sm-3 col-form-label font-weight-bold">Isi Pertanyaan <span style="color: red;">*</span>
        </label>
        <div class="col-sm-9">
            <textarea class="form-control" name="isi_pertanyaan_terbuka" value=""
                id="isi_pertanyaan"><?php echo $current->isi_pertanyaan_terbuka ?></textarea>
        </div>
    </div>



    <input type="text" name="id_jenis_jawaban" value="<?php echo $current->id_jenis_pilihan_jawaban; ?>" hidden>

    @foreach ($pilihan_jawaban->result() as $row)
    <input type="hidden" class="form-control" id="id_kategori" name="id_kategori[]"
        value="<?php echo $row->id_isi_pertanyaan_ganda; ?>">
    <div class="form-group row">
        <label class="col-sm-3 col-form-label font-weight-bold">Pilihan Jawaban
            <span style="color:red;">*</span></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="pertanyaan_ganda" name="pertanyaan_ganda[]"
                value="<?php echo $row->pertanyaan_ganda; ?>" required>
        </div>
    </div>
    @endforeach



    <div class="text-right mb-5">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary tombolDefault">Simpan</button>
    </div>
</form>



<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
<script>
ClassicEditor
    .create(document.querySelector('#isi_pertanyaan'))
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
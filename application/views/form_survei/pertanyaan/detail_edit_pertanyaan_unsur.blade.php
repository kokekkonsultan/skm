@php
$ci = get_instance();
$ci->load->helper('form');
@endphp

<form
    action="{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/form-survei/edit-pertanyaan-unsur/'.$ci->uri->segment(5)}}"
    method="POST" class="form_default">

    <input name="unsur_turunan" value="{{$pertanyaan_unsur->unsur_turunan == 1}}" hidden>

    <div class="form-group row">
        <label class="col-sm-3 col-form-label font-weight-bold">Unsur Pelayanan <span
                style="color: red;">*</span></label>
        <div class="col-sm-9">
            <div class="input-group">
                <div class="input-group-prepend"><span
                        class="input-group-text">{{$pertanyaan_unsur->nomor_unsur}}</span>
                </div>
                <input class="form-control" value="{{$pertanyaan_unsur->nama_unsur_pelayanan}}"
                    name="nama_unsur_pelayanan">
                <small>
                    Menurut Permenpan dan RB, unsur SKM terbagi 9 unsur antara lain: 1) Persyaratan 2)
                    Sistem, Mekanisme, dan Prosedur 3) Waktu Penyelesaian 4) Biaya/Tarif 5) Produk
                    Spesifikasi Jenis Pelayanan 6) Kompetensi Pelaksana 7) Perilaku Pelaksana 8) Penanganan
                    Pengaduan, Saran dan Masukan 9) Sarana dan prasarana
                </small>
            </div>
        </div>
    </div>

    @if($pertanyaan_unsur->unsur_turunan == 1)
    <div class="form-group row">
        <label class="col-sm-3 col-form-label font-weight-bold">Isi Pertanyaan <span style="color: red;">*</span>
        </label>
        <div class="col-sm-9">
            <textarea class="form-control" name="isi_pertanyaan_unsur" value=""
                id="isi_pertanyaan">{{$pertanyaan_unsur->isi_pertanyaan_unsur}}</textarea>
        </div>
    </div>

    <br>

    @foreach ($kategori_unsur->result() as $row)
    <input type="text" class="form-control" id="id_kategori" name="id_kategori[]" value="{{$row->id}}" hidden>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label font-weight-bold">Pilihan Jawaban
            <span style="color: red;">*</span></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="nama_kategori_unsur_pelayanan"
                name="nama_kategori_unsur_pelayanan[]" value="{{$row->nama_kategori_unsur_pelayanan}}" required>
        </div>
    </div>
    @endforeach
    @endif

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
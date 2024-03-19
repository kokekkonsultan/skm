@php
$ci = get_instance();

$id = $ci->uri->segment(5);
$jawaban = $ci->db->get_where("jawaban_pertanyaan_unsur_$table_identity", ['id' => $id])->row();
@endphp


<form class="form_default" action="{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/analisa-survei/update-alasan/' . $id}}" method="POST">
    <textarea class="form-control mt-3" name="alasan" rows="5">{{$jawaban->alasan_pilih_jawaban}}</textarea>

    <div class="text-right mt-5">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-sm tombolSimpanAlasan">Simpan</button>
    </div>
</form>



<script>
    $('.form_default').submit(function(e) {

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            beforeSend: function() {
                $('.tombolSimpanAlasan').attr('disabled', 'disabled');
                $('.tombolSimpanAlasan').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');
            },
            complete: function() {
                $('.tombolSimpanAlasan').removeAttr('disabled');
                $('.tombolSimpanAlasan').html('Simpan');
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
                    table.ajax.reload();
                }
            }
        })
        return false;
    });
</script>
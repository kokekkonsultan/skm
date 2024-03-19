@php
$ci = get_instance();

$id = $ci->uri->segment(5);
$survei = $ci->db->get_where("survey_$table_identity", ['id' => $id])->row();
@endphp


<form class="form_default" action="{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/analisa-survei/update-saran/' . $id}}" method="POST">
    <textarea class="form-control mt-3" name="saran" rows="5">{{$survei->saran}}</textarea>

    <div class="text-right mt-5">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-info btn-sm tombolSimpanSaran">Simpan</button>
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
                $('.tombolSimpanSaran').attr('disabled', 'disabled');
                $('.tombolSimpanSaran').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');
            },
            complete: function() {
                $('.tombolSimpanSaran').removeAttr('disabled');
                $('.tombolSimpanSaran').html('Simpan');
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
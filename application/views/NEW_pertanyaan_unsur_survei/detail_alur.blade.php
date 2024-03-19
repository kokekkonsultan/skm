@php
$ci = get_instance();
$ci->load->helper('form');
@endphp

<form
    action="{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-unsur/update-detail-alur'}}"
    method="POST" class="form_default">

    <table class="table table-bordered table-hover">
        
        <tr class="bg-secondary">
            <td class="font-weight-bold">Pilihan Jawaban</td>
            <td class="font-weight-bold">Action</td>
        </tr>


        @foreach($kategori_unsur->result() as $row)
        <tr>
            <td><input name="id_kategori[]" value="<?php echo $row->id ?>" hidden>
                <?php echo $row->nama_kategori_unsur_pelayanan ?></td>
            <td>
                <select class="form-control form-control-sm" name="is_next_step[]">

                    @php
                    $pertanyaan_terbuka = $ci->db->get_where("pertanyaan_terbuka_$manage_survey->table_identity", array('id_unsur_pelayanan' => $row->id_unsur_pelayanan));

                    $last_row = $pertanyaan_terbuka->last_row();
                    $number_next = substr($last_row->nomor_pertanyaan_terbuka, 1) + 1;
                    @endphp

                    @foreach($pertanyaan_terbuka->result() as $value)
                    <option value="{{$value->nomor_pertanyaan_terbuka}}" <?php echo $row->is_next_step == $value->nomor_pertanyaan_terbuka ? 'selected' : '' ?>>Lanjutkan Ke {{$value->nomor_pertanyaan_terbuka}}</option>
                    @endforeach

                    <option value="T{{$number_next}}" <?php echo $row->is_next_step == 'T' . $number_next ? 'selected' : '' ?>>Lanjutkan Ke Pertanyaan Unsur Berikutnya</option>

                </select>
            </td>
        </tr>
        @endforeach

    </table>


    <div class="text-right mt-8">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-sm tombolDefault">Simpan</button>
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
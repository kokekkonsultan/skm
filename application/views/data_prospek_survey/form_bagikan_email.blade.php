@php
$ci = get_instance();
$ci->load->helper('form');
@endphp

<img src="{{ base_url() }}assets/img/site/campaign/bg-bagi-link-email.jpg" class="img-fluid" alt="">



<div class="mt-5">
    @php
    echo form_open(base_url().'pengguna-klien/get-send-email', ['id' => 'confirmation_form']);
    @endphp


    <div class="text-right">
        <div class="mt-5 mb-5">
            <input type="hidden" name="email_ke" value="{{ $data_prospek->email }}">
            <label class="font-weight-bold">Isi Email</label>
            <textarea name="isi_email" class="form-control"
                style="height: 250px;">Kami Tim Survey Kepuasan Masyarakat {{ $detail->company }}, memohon kepada Bapak/ Ibu, untuk mengisi Kuesioner {{ $detail->survey_name }} dengan link berikut ini {{ $link_survey }}. Mohon diisi sebelum tanggal {{ date('d-m-Y', strtotime($detail->survey_end)) }}. Atas kesedian dan partisipasinya kami ucapkan Terima Kasih.</textarea>
        </div>
        <div>
            <label><input type="checkbox" name="is_email" value="1" checked="checked"> Kirimkan informasi</label>
        </div>
        <p>Pastikan email sudah benar dan aktif !</p>
        <button class="btn btn-light-primary font-weight-bold" id="custom-close" data-dismiss="modal"
            aria-hidden="true">Batal</button>
        <button type="submit" id="sendConfirm" class="btn btn-primary font-weight-bold">Kirim Link Survey Ke
            {{ $data_prospek->email }}</button>
    </div>

    @php
    echo form_close();
    @endphp

    <script>
    $(document).ready(function() {
        // $('#loading_registration').hide();

        $('#confirmation_form').on('submit', function(event) {

            event.preventDefault();

            $.ajax({
                url: "{{ base_url() }}data-prospek-survey/get-send-email",
                method: "POST",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function() {
                    $('#sendConfirm').attr('disabled', 'disabled');

                    Swal.fire({
                        title: 'Memproses data',
                        html: 'Mohon tunggu sebentar. Sistem sedang menyiapkan data dan mengirim email kepada penerima.',
                        onOpen: () => {
                            swal.showLoading()
                        }
                    });
                },
                success: function(data) {
                    if (data.error) {
                        if (data.name_error != '') {
                            $('#name_error').html(data.name_error);
                        }
                    }

                    if (data.success) {

                        // $('#success_message').html(data.success);
                        // $('#name_error').html('');
                        $('#confirmation_form')[0].reset();

                        // $('#main_form').hide();
                        // $('#reg_footer').hide();
                        // $('#loading_registration').show();

                        // $('#modal_userDetail').modal('hide');
                        $("#custom-close").click();


                        Swal.fire({
                            type: "success",
                            title: "Informasi",
                            text: "Berhasil menyampaikan link survey melalui email",
                            confirmButtonText: "Ya",
                        });

                        /*setTimeout(function(){  
                            $('#modal_userDetail').modal('hide');
                        }, 1000);*/
                    }

                    $('#sendConfirm').attr('disabled', true);
                    table.ajax.reload();



                }
            })
        });
    });
    </script>
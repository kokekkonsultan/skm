@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container-fluid">
    @include("include_backend/partials_no_aside/_inc_menu_repository")

    <div class="row mt-5">
        <div class="col-md-3">
            @include('manage_survey/menu_data_survey')
        </div>
        <div class="col-md-9">

            <div class="card">
                <div class="card-body" id="kt_blockui_content">
                    
                    <form action="{{ base_url() }}{{ $ci->session->userdata('username') }}/{{ $ci->uri->segment(2) }}/link-survey/update-link/do" class="form_email">
                
                    <label for="" class="font-weight-bold">Ubah Link Survei</label>
                    <div class="form-group">
                    <div class="input-group">
                     <div class="input-group-prepend"><span class="input-group-text">{{ base_url() }}survei/</span></div>
                     <input type="text" name="slug" class="form-control" value="{{ $profiles->slug_manage_survey }}">
                    </div>
                    <span class="form-text text-muted">Mengubah link survei berisiko responden anda tidak dapat mengisi survei, anda wajib menginformasikan link yang terbaru kepada responden. Jika anda mengubah link survei berarti anda mengerti risiko tersebut.</span>
                   </div>
                    


                    <div class="mt-5 text-right">
                        <a href="{{ base_url() }}{{ $ci->session->userdata('username') }}/{{ $ci->uri->segment(2) }}/link-survey" class="btn btn-light-primary font-weight-bold">Kembali</a>
                        <button type="submit" class="btn btn-primary font-weight-bold btnSave">Simpan</button>
                    </div>

                    </form>


                </div>
            </div>

        </div>
    </div>

    
</div>

@endsection

@section('javascript')

<script>
    $('.form_email').submit(function(e){

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                dataType: 'json',
                data: $(this).serialize(),
                cache: false,
                beforeSend: function(){
                    $('.btnSave').attr('disabled', 'disabled');
                    $('.btnSave').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

                    KTApp.block('#kt_blockui_content', {
                      overlayColor: '#000000',
                      state: 'primary',
                      message: 'Processing...'
                     });

                     setTimeout(function() {
                      KTApp.unblock('#kt_blockui_content');
                     }, 1000);

                },
                complete: function(){
                    $('.btnSave').removeAttr('disabled');
                    $('.btnSave').html('Simpan');
                },
                error: function(e){
                    Swal.fire(
                      'Error !',
                      e,
                      'error'
                    )
                },
                success: function(data){

                    if (data.validasi) {
                        $('.pesan').fadeIn();
                        $('.pesan').html(data.validasi);
                    }
                    if (data.sukses) {
                        
                        toastr["success"]('Link survei berhasil diubah');

                        let timerInterval
                        Swal.fire({
                          title: 'Pengaturan link survei berhasil disimpan',
                          html: 'Refresh akan dimulai beberapa saat.',
                          confirmButtonText: 'Ya, saya mengerti',
                          timer: 2000,
                          onBeforeOpen: () => {
                            
                          },
                          onClose: () => {
                            clearInterval(timerInterval)
                          }
                        }).then((result) => {
                          if (
                            result.dismiss === Swal.DismissReason.timer
                          ) {
                            // location.reload();
                            window.location.href = "{{ base_url() }}{{ $ci->session->userdata('username') }}/manage-survey";
                          }
                        });
                        
                    }

                    if (data.fail) {
                        
                        toastr["error"]('Link survei gagal diubah, link yang diinput sama dengan link sebelumnya!');

                        
                    }



                }
            })

            return false;
            
        });
</script>
@endsection

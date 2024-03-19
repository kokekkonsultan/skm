@extends('include_backend/_template')

@php
$ci = get_instance();
@endphp

@section('style')
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

<style>
    .select2-container .select2-selection--single {
        /* height: 35px; */
        font-size: 1rem;
    }
</style>
@endsection

@section('content')

<div class="container mt-5 mb-5" style="font-family: nunito;">
    <div class="text-center" data-aos="fade-up">
        <div id="progressbar" class="mb-5">
            <li class="active" id="account"><strong>Data Responden</strong></li>
            <li id="personal"><strong>Pertanyaan Survei</strong></li>
            @if($status_saran == 1)
            <li id="payment"><strong>Saran</strong></li>
            @endif
            <li id="completed"><strong>Completed</strong></li>
        </div>
    </div>
    <br>
    <br>

    <div class="row">
        <div class="col-md-8 offset-md-2" style="font-size: 16px; font-family:arial, helvetica, sans-serif;">
            <div class="card shadow mb-4 mt-4" id="kt_blockui_content" data-aos="fade-up">


                @if($manage_survey->img_benner == '')
                <img class="card-img-top" src="{{ base_url() }}assets/img/site/page/banner-survey.jpg" alt="new image" />
                @else
                <img class="card-img-top shadow" src="{{ base_url() }}assets/klien/benner_survei/{{$manage_survey->img_benner}}" alt="new image">
                @endif


                <div class="card-header text-center">
                    <h3 class="mt-5" style="font-family: 'Exo 2', sans-serif;"><b>DATA RESPONDEN</b></h3>
					@include('include_backend/partials_backend/_tanggal_survei')
                </div>

                <form action="{{base_url() . 'survei/' . $ci->uri->segment(2) . '/data-responden/' . $ci->uri->segment(4) . '/update'}}" class="form_responden" method="POST">

                    <div class="card-body">

                        @include('include_backend/partials_backend/_message')


                        <span style="color: red; font-style: italic;">{!! validation_errors() !!}</span>


                        @if($manage_survey->is_layanan_survei != 0)
                        <div class="form-group">
                            <label class="font-weight-bold">Jenis Pelayanan yang diterima <span class="text-danger">*</span></label>

                            {!! form_dropdown($id_layanan_survei); !!}


                            <!-- @if($manage_survey->is_kategori_layanan_survei == 1)
                            <div class="accordion accordion-solid accordion-panel accordion-svg-toggle mb-10" id="faq">
                                @php
                                $kategori_layanan = $ci->db->query("SELECT * FROM
                                kategori_layanan_survei_$manage_survey->table_identity WHERE is_active = 1 ORDER BY
                                urutan ASC");

                                $layanan_dipilih = $ci->db->get_where("layanan_survei_$manage_survey->table_identity",
                                array('id' => $responden->id_layanan_survei))->row();
                                @endphp

                                @foreach($kategori_layanan->result() as $row)
                                <div class="card p-1 shadow">
                                    <div class="card-header" id="faqHeading{{$row->id}}">
                                        <div class="card-title collapsed" data-toggle="collapse"
                                            data-target="#faq{{$row->id}}" aria-expanded="false"
                                            aria-controls="faq{{$row->id}}" role="button"
                                            onclick="clearField({{$row->id}})">
                                            <div class="card-label" style="font-size: 1rem; color: #3F4254;">
                                                {{$row->nama_kategori_layanan}}</div>
                                            <span class="svg-icon svg-icon-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                    height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <polygon points="0 0 24 0 24 24 0 24" />
                                                        <path
                                                            d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z"
                                                            fill="#000000" fill-rule="nonzero" />
                                                        <path
                                                            d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z"
                                                            fill="#000000" fill-rule="nonzero" opacity="0.3"
                                                            transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999)" />
                                                    </g>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>

                                    <div id="faq{{$row->id}}"
                                        class="collapse {{ $row->id == $layanan_dipilih->id_kategori_layanan ? 'show' : '' }}"
                                        aria-labelledby="faqHeading{{$row->id}}" data-parent="#faq">
                                        <div class="card-body pt-3 font-size-h6 font-weight-normal text-dark-50">

                                            <select class="form-control" name="id_layanan_survei[]"
                                                id="id_layanan_survei<?= $row->id ?>" style="width:100%;">
                                                <option value="">Please Select</option>
                                                @foreach($ci->db->query("SELECT * FROM
                                                layanan_survei_$manage_survey->table_identity WHERE id_kategori_layanan
                                                = $row->id && is_active = 1 ORDER BY urutan ASC")->result() as $value)
                                                <option value="{{$value->id}}"
                                                    <?= $value->id == $responden->id_layanan_survei ? 'selected' : '' ?>>
                                                    {{$value->nama_layanan}}
                                                </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else

                            {!! form_dropdown($id_layanan_survei); !!}

                            @endif -->


                        </div>
                        <br>
                        @endif





                        @foreach ($profil_responden->result() as $row)
                        @php
                        $nama_alias = $row->nama_alias;
                        $nama_alias_lainnya = $row->nama_alias. '_lainnya';
                        @endphp

                        <div class="form-group">
                            <label class="font-weight-bold">{{$row->nama_profil_responden}}<span class="text-danger">*</span></label>

                            @if ($row->jenis_isian == 2)
                            <input class="form-control" type="<?php echo $row->type_data ?>" name="<?php echo $row->nama_alias ?>" placeholder="Masukkan data anda ..." value="<?php echo $responden->$nama_alias ?>" required>

                            @else
                            <select class="form-control" name="{{$row->nama_alias}}" id="{{$row->nama_alias}}" required>
                                <option value="">Please Select</option>

                                @foreach ($kategori_profil_responden->result() as $value)
                                @if ($value->id_profil_responden == $row->id)

                                <option value="{{$value->id}}" id="{{$value->nama_kategori_profil_responden}}" <?php echo $responden->$nama_alias == $value->id ? 'selected' : '' ?>>
                                    {{$value->nama_kategori_profil_responden}}
                                </option>

                                @endif
                                @endforeach

                            </select>

                            @if ($row->is_lainnya == 1)
                            <input class="form-control mt-5" type="text" name="{{$row->nama_alias}}_lainnya" id="{{$row->nama_alias}}_lainnya" placeholder="Sebutkan Lainnya ..." value="<?php echo $responden->$nama_alias_lainnya ?>" <?php echo $responden->$nama_alias_lainnya == '' ? 'style="display: none;"' : ' required' ?>>
                            @endif

                            @endif
                        </div>

                        </br>
                        @endforeach


                    </div>
                    <div class="card-footer">
                        <table class="table table-borderless">
                            <tr>
                                <!-- <td class="text-left">
                                    {!! anchor(base_url().'survei/'.$ci->uri->segment(2), '<i class="fa fa-arrow-left"></i>
                                    Kembali',
                                    ['class' => 'btn btn-secondary btn-lg font-weight-bold shadow tombolCancel']) !!}
                                </td> -->
                                <td class="text-right">
                                    <button type="submit" class="btn btn-warning btn-lg font-weight-bold shadow tombolSave" onclick="preventBack()">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>

            <br><br>
        </div>
    </div>
</div>


@endsection

@section('javascript')

<script>
    $('.form_responden').submit(function(e) {

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            beforeSend: function() {
                $('.tombolCancel').attr('disabled', 'disabled');
                $('.tombolSave').attr('disabled', 'disabled');
                $('.tombolSave').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

                KTApp.block('#kt_blockui_content', {
                    overlayColor: '#FFA800',
                    state: 'primary',
                    message: 'Processing...'
                });

                setTimeout(function() {
                    KTApp.unblock('#kt_blockui_content');
                }, 500);

            },
            complete: function() {
                $('.tombolCancel').removeAttr('disabled');
                $('.tombolSave').removeAttr('disabled');
                $('.tombolSave').html('Selanjutnya <i class="fa fa-arrow-right"></i>');
            },

            error: function(e) {
                Swal.fire(
                    'Gagal Menyimpan Data Survei!',
                    e,
                    'error'
                );
                setTimeout(function() {
                    location.reload();
                }, 500);
            },

            success: function(data) {
                if (data.validasi) {
                    $('.pesan').fadeIn();
                    $('.pesan').html(data.validasi);
                }
                if (data.sukses) {
                    // toastr["success"]('Data berhasil disimpan');

                    setTimeout(function() {
                        window.location.href =
                            "<?php echo base_url() . 'survei/' . $ci->uri->segment(2) . '/pertanyaan/' . $ci->uri->segment(4) . '/edit' ?>";
                    }, 500);
                }
            }
        })
        return false;
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
@php
$profil_responden_js = $ci->db->query("SELECT * FROM
profil_responden_$manage_survey->table_identity WHERE jenis_isian = 1 && is_lainnya = 1");
@endphp

@foreach($profil_responden_js->result() as $pr_js)
<script type='text/javascript'>
    $(window).load(function() {
        $("#{{$pr_js->nama_alias}}").change(function() {
            console.log(document.getElementById("{{$pr_js->nama_alias}}").options['Lainnya'].selected);

            if (document.getElementById("{{$pr_js->nama_alias}}").options['Lainnya'].selected == true) {
                $('#{{$pr_js->nama_alias}}_lainnya').show().prop('required', true);
            } else {
                $('#{{$pr_js->nama_alias}}_lainnya').removeAttr('required').hide();
            }
        });
    });
</script>
@endforeach


<script type="text/javascript">
    function preventBack() {
        window.history.forward();
    }
    setTimeout("preventBack()", 0);
    window.onunload = function() {
        null
    };
</script>


<script>
    $(document).ready(function() {
        $("#id_layanan_survei").select2({
            placeholder: "   Please Select",
            allowClear: true,
            closeOnSelect: true,
        });
    });
</script>

<!-- @if($manage_survey->is_kategori_layanan_survei == 1)
@foreach($kategori_layanan->result() as $row)
<script>
$(document).ready(function() {
    $("#id_layanan_survei<?= $row->id ?>").select2({
        placeholder: "   Please Select",
        allowClear: true,
        closeOnSelect: true,
    });
});
</script>
@endforeach
@endif

<script>
function clearField($d) {
    $('#id_layanan_survei' + $d).select2();
    $('#id_layanan_survei' + $d).val(null).trigger("change");
}
</script> -->

@endsection
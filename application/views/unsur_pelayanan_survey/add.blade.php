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

            <div class="card" data-aos="fade-down">
                <div class="card-header bg-secondary">
                    <h5>{{ $title }}</h5>
                </div>
                <div class="card-body">

                    <?php echo form_open(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/unsur-pelayanan-survey/add'); ?>

                    <br>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label
                        font-weight-bold">Unsur Pelayanan <span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            @php
                            echo form_input($nama_unsur_pelayanan);
                            @endphp
                            <small>
                                Menurut Permenpan dan RB, unsur SKM terbagi 9 unsur antara lain: 1) Persyaratan 2)
                                Sistem, Mekanisme, dan Prosedur 3) Waktu Penyelesaian 4) Biaya/Tarif 5) Produk
                                Spesifikasi Jenis Pelayanan 6) Kompetensi Pelaksana 7) Perilaku Pelaksana 8) Penanganan
                                Pengaduan, Saran dan Masukan 9) Sarana dan prasarana
                            </small>
                        </div>
                    </div>

                    <br>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Sub Unsur Pelayanan <span
                                style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <div><label>
                                    <input type="radio" name="custom" id="default" value="2" class="custom"
                                        required="required">&nbsp
                                    Tanpa Sub
                                    Unsur</label><br>
                            </div>
                            <div><label>
                                    <input type="radio" name="custom" id="custom" value="1" class="custom"
                                        required="required">&nbsp
                                    Dengan Sub
                                    Unsur</label><br>
                            </div>
                            <div class="mb-3">
                                @php
                                echo form_dropdown($id_parent);
                                @endphp
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        @php
                        echo
                        anchor(base_url().$ci->session->userdata('username').'/'.$ci->uri->segment(2).'/unsur-pelayanan-survey',
                        'Batal', ['class' => 'btn btn-light-primary font-weight-bold shadow-lg'])
                        @endphp
                        <?php echo form_submit('submit', 'Simpan', ['class' => 'btn btn-primary font-weight-bold shadow-lg']); ?>
                    </div>

                    <?php echo form_close(); ?>
                </div>
            </div>

        </div>
    </div>


</div>

@endsection

@section ('javascript')


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script type="text/javascript">
$(function() {
    $(":radio.custom").click(function() {
        $("#id_parent").hide()
        if ($(this).val() == "1") {
            $("#id_parent").show().prop('required', true);
        } else {
            $("#id_parent").removeAttr('required').hidden();
        }
    });
});
</script>

@endsection
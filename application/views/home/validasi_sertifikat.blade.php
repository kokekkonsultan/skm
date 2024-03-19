@extends('include_frontend/template_frontend')

@php
$ci = get_instance();
@endphp

@section('style')

<style>
.header_card {
    color: #ffffff;
    background: linear-gradient(105deg, rgba(0 158 247) 0%, rgba(0, 247, 218) 100%);
    font-family: montserrat;
    font-size: 20px;
}
</style>

@endsection

@section('content')
<div class="main-content wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s">

    <div class="card shadow aos-init aos-animate" style="border-radius: 25px;">
        <div class="card-header fw-bold shadow text-center header_card" style="border-radius: 25px;">
            DETAIL SERTIFIKAT

        </div>

        <div class="card-body mt-3 mb-3" style="padding-left: 50px;">

            <div class="text-left mb-3" style="width: 175px;">
                <?php if ($user->foto_profile == NULL) : ?>
                <img class="card-img-top" src="{{ base_url() }}assets/klien/foto_profile/200px.jpg" alt="Card image">
                <?php else : ?>
                <img class="card-img-top"
                    src="<?php echo base_url(); ?>assets/klien/foto_profile/<?php echo $user->foto_profile ?>"
                    alt="Card image">
                <?php endif; ?>
            </div>
            <br>

            <div class="form-group row mb-3">
                <div class="col-sm-3" style="font-weight:bold;">
                    Pemegang Sertifikat
                </div>
                <div class="col-sm-1">
                    :
                </div>
                <div class="col-sm-8">
                    <?php echo $user->company ?>
                </div>
            </div>
            <div class="form-group row mb-3">
                <div class="col-sm-3" style="font-weight:bold;">
                    Nomor Sertifikat
                </div>
                <div class="col-sm-1">
                    :
                </div>
                <div class="col-sm-8">
                    <?php echo $manage_survey->nomor_sertifikat ?>
                </div>
            </div>
            <div class="form-group row mb-3">
                <div class="col-sm-3" style="font-weight:bold;">
                    Nama Survei
                </div>
                <div class="col-sm-1">
                    :
                </div>
                <div class="col-sm-8">
                    <?php echo $manage_survey->survey_name ?>
                </div>
            </div>
            <div class="form-group row mb-3">
                <div class="col-sm-3" style="font-weight:bold;">
                    Tanggal Survei
                </div>
                <div class="col-sm-1">
                    :
                </div>
                <div class="col-sm-8">
                    <?php echo $manage_survey->survey_mulai ?> s/d
                    <?php echo $manage_survey->survey_selesai ?>
                </div>
            </div>
            <div class="form-group row mb-3">
                <div class="col-sm-3" style="font-weight:bold;">
                    Jenis Pelayanan
                </div>
                <div class="col-sm-1">
                    :
                </div>
                <div class=" col-sm-8">
                    <?php echo $manage_survey->nama_klasifikasi_survei ?> -
                    <?php echo $manage_survey->nama_jenis_pelayanan_responden ?>
                </div>
            </div>
            <div class="form-group row mb-3">
                <div class="col-sm-3" style="font-weight:bold;">
                    Metode Sampling
                </div>
                <div class="col-sm-1">
                    :
                </div>
                <div class=" col-sm-8">
                    <?php echo $manage_survey->nama_sampling ?>
                </div>
            </div>
            <div class="form-group row mb-3">
                <div class="col-sm-3" style="font-weight:bold;">
                    Sample Minimal
                </div>
                <div class="col-sm-1">
                    :
                </div>
                <div class=" col-sm-8">
                    <?php echo $manage_survey->jumlah_sampling ?> Orang
                </div>
            </div>

        </div>
    </div>


    <div class="row mt-5 mb-5">
        <div class="col-md-5">
            <div class="card shadow aos-init aos-animate" style="border-radius: 25px;">
                <div class="card-header fw-bold shadow text-center header_card" style="border-radius: 25px;">
                    NILAI IKM
                </div>
                <div class="card-body mt-3 mb-3">

                    <div class="text-center" style="font-weight: bold; font-size:50px;">
                        <?php echo ROUND($ikm, 2) ?></div>
                    <div class="text-center" style="font-size:16px;">Predikat : <br>
                        <b> <?php
                            foreach ($definisi_skala->result() as $obj) {
                                if ($ikm <= $obj->range_bawah && $ikm >= $obj->range_atas) {
                                    echo  $obj->kategori;
                                }
                            }
                            if ($ikm <= 0 || $ikm == NULL) {
                                echo  'NULL';
                            }

                            // if ($ikm <= 100 && $ikm >= 88.31) {
                            //     echo 'SANGAT BAIK';
                            // } elseif ($ikm <= 88.40 && $ikm >= 76.61) {
                            //     echo 'BAIK';
                            // } elseif ($ikm <= 76.60 && $ikm >= 65) {
                            //     echo 'KURANG BAIK';
                            // } elseif ($ikm <= 64.99 && $ikm >= 25) {
                            //     echo 'TIDAK BAIK';
                            // } else {
                            //     echo 'NULL';
                            // }
                            ?></b>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card shadow aos-init aos-animate" style="border-radius: 25px;">
                <div class="card-header fw-bold shadow text-center header_card" style="border-radius: 25px;">
                    RESPONDEN
                </div>
                <div class="card-body mt-3 mb-3" style="padding-left: 50px;">

                    <div class="mb-3"><b>JUMLAH RESPONDEN :</b> <?php echo $jumlah_kuisioner ?> Orang</div>

                    <?php
                    foreach ($profil->result() as  $row) {
                    ?>
                    <div><b><?php echo $row->nama_profil ?></b>

                        <ul style="padding-left: 30px;">
                            <?php
                                $kategori_profil_responden = $ci->db->query("SELECT *, (SELECT COUNT(*) FROM responden_$manage_survey->table_identity JOIN survey_$manage_survey->table_identity ON responden_$manage_survey->table_identity.id = survey_$manage_survey->table_identity.id_responden WHERE kategori_profil_responden_$manage_survey->table_identity.id = responden_$manage_survey->table_identity.$row->nama_alias && is_submit = 1) AS perolehan FROM kategori_profil_responden_$manage_survey->table_identity");

                                foreach ($kategori_profil_responden->result() as $value) {
                                ?>
                            <?php if ($value->id_profil_responden == $row->id) { ?>

                            <li><?php echo $value->nama_kategori_profil_responden ?> :
                                <?php echo $value->perolehan ?> Orang</li>

                            <?php } ?>

                            <?php } ?>

                        </ul>
                    </div>

                    <?php } ?>

                    <div class="mt-3"><b>PERIODE SURVEI :</b> <?php echo $manage_survey->survey_mulai ?> s/d
                        <?php echo $manage_survey->survey_selesai ?></div>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('javascript')

@endsection
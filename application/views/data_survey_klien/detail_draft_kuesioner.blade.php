@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection
<style>
table {
    border-collapse: collapse;
    font-family: sans-serif;
    font-size: .8rem;
}

table,
th,
td {
    border: 1px solid black;
}

th,
td {
    padding: 3px;
}

header {
    position: fixed;
}
</style>
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">


            @include('data_survey_klien/menu_data_survey_klien')

        </div>
        <div class="col-md-9">
            <div class="card" data-aos="fade-down" data-aos-delay="300">
                <div class="card-header font-weight-bold">
                    {{ $title }}
                </div>
                <div class="card-body">



                    <table style="width: 100%;">
                        <tr>
                            <td width="50%">
                                <table style="border: 1px solid white;">
                                    <tr style="border: 1px solid white;">
                                        <td width="10%" style="border: 1px solid white; padding-left: 8px;">
                                            <?php if ($user->foto_profile == NULL) : ?>
                                            <img src="<?php echo base_url() ?>assets/klien/foto_profile/200px.jpg"
                                                height="75" alt="">
                                            <?php else : ?>
                                            <img src="<?php echo base_url(); ?>assets/klien/foto_profile/<?php echo $user->foto_profile ?>"
                                                height="75" alt="">
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-left" style="border: 1px solid white;">
                                            <div style="font-size:14px; font-weight:bold; padding-left: 8px;">
                                                <?php echo $title_header_survey ?>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td width="50%">
                                <div
                                    style="font-weight:bold; margin: auto; padding: 10px;  font-size:15px; text-align: center;">
                                    SURVEI
                                    KEPUASAN MASYARAKAT</div>
                            </td>
                        </tr>
                    </table>

                    <table style="width: 100%;">
                        <tr>
                            <td
                                style="text-align:center; font-size: 11px; font-family:Arial, Helvetica, sans-serif; height:35px;">
                                Dalam
                                rangka
                                meningkatkan
                                kepuasan masyarakat, Saudara dipercaya
                                menjadi responden pada kegiatan
                                survei ini.<br>
                                Atas kesediaan Saudara kami sampaikan terima kasih dan penghargaan sedalam-dalamnya.
                            </td>
                        </tr>
                    </table>

                    <table style="width: 100%;">
                        <tr>
                            <td colspan="2"
                                style="text-align:left; font-size: 11px; background-color: black; color:white;">
                                DATA RESPONDEN
                            </td>
                        </tr>

                        <tr style="font-size: 11px;">
                            <td width=" 30%" style="height:15px;">Nama</td>
                            <td width="70%"></td>
                        </tr>

                        <?php
						foreach ($profil_responden as $get_profil) {
						?>
                        <tr style="font-size: 11px;">
                            <td width=" 30%" style="height:15px;"> <?php echo $get_profil->nama_profil_responden ?></td>
                            <td width="70%"></td>
                        </tr>
                        <?php
						} ?>

                        <tr style="font-size: 11px;">
                            <td width=" 30%" style="height:15px;">Waktu Isi Survei</td>
                            <td width="70%"></td>
                        </tr>
                    </table>

                    <table style="width: 100%;">
                        <tr>
                            <td colspan="2"
                                style="text-align:left; font-size: 11px; background-color: black; color:white;">
                                PENILAIAN TERHADAP UNSUR-UNSUR KEPUASAN MASYARAKAT (isi kolom dengan tanda "X" sesuai
                                jawaban Saudara)
                            </td>
                        </tr>
                    </table>

                    <table width="100%" style="font-size: 11px; text-align:center; background-color:#C7C6C1">
                        <tr>
                            <td rowspan="2" width="5%">No</td>
                            <td rowspan="2" width="32%">PERTANYAAN</td>
                            <td colspan="4" width="40%">PILIHAN JAWABAN</td>
                            <td rowspan="2" width="23%">Berikan alasan jika pilihan jawaban: 1 atau 2
                            </td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                            <td>4</td>
                        </tr>
                    </table>

                    <table width="100%" style="font-size: 11px; text-align:center;">

                        <?php
						foreach ($pertanyaan->result() as $row) {
						?>
                        <tr height="5%">
                            <td rowspan="2">
                                <?php echo $row->nomor_unsur ?>
                            </td>
                            <td width="32%" rowspan="2" style="text-align:left;">
                                <?php echo $row->isi_pertanyaan_unsur ?>
                            </td>

                            <td width="10%" style="background-color:#C7C6C1"><?php echo $row->pilihan_1 ?></td>
                            <td width="10%" style="background-color:#C7C6C1"><?php echo $row->pilihan_2 ?></td>
                            <td width="10%" style="background-color:#C7C6C1"><?php echo $row->pilihan_3 ?></td>
                            <td width="10%" style="background-color:#C7C6C1"><?php echo $row->pilihan_4 ?></td>

                            <td width="23%" rowspan="2"> </td>
                        </tr>

                        <tr height="95%">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <?php
						} ?>

                        <tr>
                            <th colspan="7" style="text-align:left;">
                                SARAN :
                                <br />
                                <br />
                                <br />
                            </th>
                        </tr>
                        <tr>
                            <td colspan="7" style="text-align:center;">
                                Terima kasih atas kesediaan Saudara mengisi kuesioner tersebut di atas.<br>
                                Saran dan penilaian Saudara memberikan konstribusi yang sangat berarti bagi peningkatan
                                kepuasan
                                masyarakat.
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('javascript')

@endsection
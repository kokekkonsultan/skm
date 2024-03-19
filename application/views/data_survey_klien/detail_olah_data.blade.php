@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
    type="text/css" />
@endsection

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">

            @include('data_survey_klien/menu_data_survey_klien')
        </div>
        <div class="col-md-9">

            <div class="card card-custom card-sticky" data-aos="fade-down">
                @include('include_backend/partials_backend/_message')
                <div class="card-header">
                    <div class="card-title">
                        {{$title}}
                    </div>
                    <div class="card-toolbar">
                        <button class="btn btn-primary btn-sm" disabled>
                            <strong><?php echo $jumlah_kuisioner; ?></strong> Kuesioner Sudah Terisi
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%"
                            style="font-size: 12px;">
                            <thead class="bg-secondary">
                                <tr>
                                    <th width="5%">No.</th>
                                    <th>Status</th>
                                    <th>Surveyor</th>
                                    <th>Nama Lengkap</th>

                                    @foreach ($unsur->result() as $row)
                                    <th><?php echo $row->nomor_unsur ?></th>
                                    @endforeach

                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card card-body mt-5" data-aos="fade-down">
                <h3>Persepsi</h3>
                <div class="table-responsive">
                    <table width="100%" class="table table-bordered" style="font-size: 12px;">
                        <tr align="center" class="bg-primary text-white">
                            <th></th>
                            <?php foreach ($unsur->result() as $row) { ?>
                            <th><?php echo $row->nomor_unsur ?></th>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td class="bg-secondary"><strong>TOTAL</strong></td>
                            <?php
                            foreach ($total->result() as $total) {
                            ?>
                            <td>
                                <div align="center">
                                    <strong><?php echo ROUND($total->sum_skor_jawaban, 3) ?></strong>
                                </div>
                            </td>
                            <?php
                            } ?>
                        </tr>

                        <tr>
                            <td class="bg-secondary"><strong>Rata-Rata</strong></td>
                            <?php
                            foreach ($rata_rata->result() as $rata_rata) {
                            ?>
                            <td>
                                <div align="center"><?php echo ROUND($rata_rata->rata_rata, 3) ?></div>
                            </td>
                            <?php
                            } ?>
                        </tr>

                        <tr>
                            <td class="bg-secondary"><strong>Nilai per Unsur</strong></td>
                            <?php
                            foreach ($nilai_per_unsur->result() as $nilai_per_unsur) {
                            ?>
                            <td colspan="<?php echo $nilai_per_unsur->colspan ?>">
                                <div align="center">
                                    <strong><?php echo ROUND($nilai_per_unsur->nilai_per_unsur, 3) ?></strong>
                                </div>
                            </td>
                            <?php
                            } ?>
                        </tr>

                        <tr>
                            <td class="bg-secondary"><strong>Rata-Rata * Bobot</strong></td>
                            <?php
                            foreach ($rata_rata_bobot->result() as $rata_rata_bobot) {
                                $nilai_bobot[] = $rata_rata_bobot->rata_rata_bobot;
                                $nilai_tertimbang = array_sum($nilai_bobot);
                                $ikm = $nilai_tertimbang * 25;
                            ?>
                            <td colspan="<?php echo $rata_rata_bobot->colspan ?>">
                                <div align="center"><?php echo ROUND($rata_rata_bobot->rata_rata_bobot, 3) ?></div>
                            </td>
                            <?php
                            } ?>
                        </tr>

                        <tr>
                            <td class="bg-secondary"><strong>Nilai Rata2 Tertimbang</strong></td>
                            <td colspan="<?php echo $tertimbang->colspan ?>">
                                <div align="center"><?php echo ROUND($nilai_tertimbang, 3) ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-secondary"><strong>IKM</strong></td>
                            <td colspan="<?php echo $tertimbang->colspan ?>">
                                <div align="center"> <strong><?php echo ROUND($ikm, 3) ?></strong></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-secondary"><strong>MUTU PELAYANAN</strong></td>
                            <td colspan="<?php echo $tertimbang->colspan ?>">
                                <div align="center"><strong>

                                        <?php if ($ikm <= 100 && $ikm >= 88.31) {
                                            echo 'Sangat Baik';
                                        } elseif ($ikm <= 88.40 && $ikm >= 76.61) {
                                            echo 'Baik';
                                        } elseif ($ikm <= 76.60 && $ikm >= 65) {
                                            echo 'Kurang Baik';
                                        } elseif ($ikm <= 64.99 && $ikm >= 25) {
                                            echo 'Tidak Baik';
                                        } else {
                                            echo 'NULL';
                                        }
                                        ?>
                                    </strong></div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>


            @if(in_array(1, $atribut_pertanyaan))
            <div class="card card-body mt-5" data-aos="fade-down">
                <h3>Harapan</h3>
                <div class="table-responsive">
                    <table width="100%" class="table table-bordered" style="font-size: 12px;">
                        <tr align="center" class="bg-primary text-white">
                            <th></th>
                            <?php foreach ($unsur->result() as $row) { ?>
                            <th>H<?php echo $row->nomor_harapan ?></th>
                            <?php } ?>
                        </tr>

                        <tr>
                            <td class="bg-secondary"><strong>TOTAL</strong></td>
                            <?php
                            foreach ($total_harapan->result() as $total_harapan) {
                            ?>
                            <td>
                                <div align="center">
                                    <strong><?php echo ROUND($total_harapan->sum_skor_jawaban, 3) ?></strong>
                                </div>
                            </td>
                            <?php
                            } ?>
                        </tr>

                        <tr>
                            <td class="bg-secondary"><strong>Rata-Rata</strong></td>
                            <?php
                            foreach ($rata_rata_harapan->result() as $rata_rata_harapan) {
                            ?>
                            <td>
                                <div align="center"><?php echo ROUND($rata_rata_harapan->rata_rata, 3) ?></div>
                            </td>
                            <?php
                            } ?>
                        </tr>

                        <tr>
                            <td class="bg-secondary"><strong>Rata-Rata per Harapan</strong>
                            </td>
                            <?php
                            foreach ($nilai_per_unsur_harapan->result() as $nilai_per_unsur_harapan) {
                            ?>
                            <td colspan="<?php echo $nilai_per_unsur_harapan->colspan ?>">
                                <div align="center">
                                    <strong><?php echo ROUND($nilai_per_unsur_harapan->nilai_per_unsur, 3) ?></strong>
                                </div>
                            </td>
                            <?php
                            } ?>
                        </tr>



                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>

</div>

@endsection

@section('javascript')
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>
<script>
$(document).ready(function() {
    table = $('#table').DataTable({

        "processing": true,
        "serverSide": true,
        "lengthMenu": [
            [5, 10, 25, 50, 100, -1],
            [5, 10, 25, 50, 100, "Semua data"]
        ],
        "pageLength": 5,
        "order": [],
        "language": {
            "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
        },
        "ajax": {
            "url": "<?php echo base_url() . 'data-survey-klien/ajax-list-olah-data/' . $ci->uri->segment(3) ?>",
            "type": "POST",
            "data": function(data) {}
        },

        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }, ],

    });
});

$('#btn-filter').click(function() {
    table.ajax.reload();
});
$('#btn-reset').click(function() {
    $('#form-filter')[0].reset();
    table.ajax.reload();
});
</script>
@endsection
@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">

            <div class="card card-custom bgi-no-repeat gutter-b" style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/rhone-2.svg)" data-aos="fade-down">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <h3 class="text-white font-weight-bolder line-height-lg mb-5">
                            TABULASI & {{strtoupper($title)}} <?php echo strtoupper($nama_survey); ?>
                        </h3>

                        <a class="btn btn-light-primary btn-sm" href="{{base_url() . 'olah-data-per-bagian'}}"><i class="fa fa-arrow-left"></i> Kembali</a>

                        <span class="btn btn-secondary btn-sm disable">
                            <i class="fa fa-bookmark"></i> <b><?php echo $jumlah_kuesioner_terisi ?>
                                Kuesioner Terisi</b></span>

                    </div>
                </div>
            </div>


            @if($jumlah_kuesioner_terisi > 0)


            <div class="card card-custom card-sticky" data-aos="fade-down">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%" style="font-size: 12px;">
                            <thead class="bg-secondary">
                                <tr>
                                    <th width="5%">No.</th>
                                    <!-- <th>Status</th>
                                    <th>Surveyor</th> -->
                                    <!-- <th>Nama Lengkap</th> -->
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
                        <tr align="center">
                            <th></th>
                            @foreach ($unsur->result() as $row)
                            <th class="bg-primary text-white">{{ $row->nomor_unsur }}</th>
                            @endforeach
                        </tr>
                        <tr>
                            <th class="bg-secondary">TOTAL</th>
                            @foreach ($total->result() as $total)
                            <th class="text-center">{{ ROUND($total->sum_skor_jawaban, 3) }}</th>
                            @endforeach
                        </tr>
                        <tr>
                            <th class="bg-secondary">Jumlah Kuesioner</th>
                            @foreach ($jumlah_kuisioner_per_unsur->result() as $jumlah_kuisioner_per_unsur)
                            <th class="text-center">{{ ROUND($jumlah_kuisioner_per_unsur->jumlah_kuisioner, 3) }}</th>
                            @endforeach
                        </tr>

                        <tr>
                            <th class="bg-secondary">Rata-Rata</th>
                            @foreach ($rata_rata->result() as $rata_rata)
                            <td class="text-center">{{ ROUND($rata_rata->rata_rata, 3) }}</td>
                            @endforeach
                        </tr>

                        <tr>
                            <th class="bg-secondary">Nilai per Unsur</th>
                            @foreach ($nilai_per_unsur->result() as $nilai_per_unsur)
                            <th colspan="{{ $nilai_per_unsur->colspan }}" class="text-center">
                                {{ ROUND($nilai_per_unsur->nilai_per_unsur, 3) }}
                            </th>
                            @endforeach
                        </tr>

                        <tr>
                            <th class="bg-secondary">Rata-Rata * Bobot</th>
                            <?php
                            $c=0;
                            foreach ($rata_rata_bobot->result() as $rata_rata_bobot) {
                                $c++;
                                $nilai_bobot[] = $rata_rata_bobot->rata_rata_bobot;
                                $nilai_tertimbang = array_sum($nilai_bobot);
                                $ikm = ROUND($nilai_tertimbang * $skala_likert, 10);
                                // $ikm = 80;
                                ?>
                                <td colspan="{{ $rata_rata_bobot->colspan }}" class="text-center">
                                    {{ ROUND($rata_rata_bobot->rata_rata_bobot, 3) }}
                                </td>
                            <?php } ?>
                        </tr>

                        <tr>
                            <th class="bg-secondary">Nilai Rata2 Tertimbang</th>
                            <td colspan="{{ $c }}{{-- $tertimbang->colspan --}}">{{ ROUND($nilai_tertimbang, 3) }}</td>
                        </tr>
                        <tr>
                            <th class="bg-secondary">IKM</th>
                            <th colspan="{{ $c }}{{-- $tertimbang->colspan --}}">{{ROUND($ikm, 3)}}</th>
                        </tr>

                        <!-- =IF(K510>4,5;"Pelayanan Prima";
                        IF(K510>4;"Sangat Baik";
                        IF(K510>3,5;"Baik";
                        IF(K510>3;"Baik (Dengan Catatan)";
                        IF(K510>2,5;"Cukup";
                        IF(K510>2;"Cukup (Dengan Catatan)";
                        IF(K510>1,5;"Buruk";
                        IF(K510>1;"Sangat Buruk";
                        IF(K510>0;"Terlalu Buruk"))))))))) -->


                        <?php
                        // if ($ikm <= 100 && 81 <= $ikm) {
                        //     $kategori = 'A';
                        //     $mutu = 'A';
                        // } elseif ($ikm <= 80 && 61 <= $ikm) {
                        //     $kategori = 'B';
                        //     $mutu = 'B';
                        // } elseif ($ikm <= 60 && 41 <= $ikm) {
                        //     $kategori = 'C';
                        //     $mutu = 'C';
                        // } elseif ($ikm <= 40 && 21 <= $ikm) {
                        //     $kategori = 'D';
                        //     $mutu = 'D';
                        // } elseif ($ikm <= 20 && 0 <= $ikm) {
                        //     $kategori = 'E';
                        //     $mutu = 'E';
                        // } else {
                        //     $kategori = 'NULL';
                        //     $mutu = 'NULL';
                        // }

                        foreach ($definisi_skala->result() as $obj) {
                            if ($ikm <= $obj->range_bawah && $ikm >= $obj->range_atas) {
                                $kategori = $obj->kategori;
                                $mutu = $obj->mutu;
                            }
                        }
                        if ($ikm <= 0) {
                            $kategori = 'NULL';
                            $mutu = 'NULL';
                        }
                        ?>

                        <tr>
                            <th class="bg-secondary">MUTU PELAYANAN</th>
                            <th colspan="{{ $c }}{{-- $tertimbang->colspan --}}">{{$mutu}}</th>
                        </tr>

                        <tr>
                            <th class="bg-secondary">KATEGORI</th>
                            <th colspan="{{ $c }}{{-- $tertimbang->colspan --}}">{{$kategori}}</th>
                        </tr>
                    </table>
                </div>
            </div>

            @else

            <div class="card card-body">
                <div class="text-danger text-center"><i>Belum ada data responden yang sesuai.</i></div>
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
                //"url": "<?php echo base_url() . 'olah-data-per-bagian/' . $ci->uri->segment(2) . '/' . $ci->uri->segment(3) . '/ajax-detail' ?>",
                "url": "<?php echo base_url() . $ci->uri->segment(2) . '/' . $ci->uri->segment(3) . '/olah-data/ajax-list' ?>",
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
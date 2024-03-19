@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">

            @include('data_survey_klien/menu_data_survey_klien')

        </div>
        <div class="col-md-9">
            <div class="card card-custom card-sticky mb-5" data-aos="fade-down" data-aos-delay="300">
                <div class="card-header">
                    <div class="card-title">
                        Deskripsi Survei
                    </div>
                    <div class="card-toolbar">
                    </div>
                </div>
                <div class="card-body">

                    <table class="table">
                        <tr>
                            <th>Nama Survei</th>
                            <td>{{ $manage_survey->survey_name }}</td>
                        </tr>
                        <tr>
                            <th>Organisasi Yang di Survei</th>
                            <td>{!! $manage_survey->organisasi !!}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{!! $manage_survey->alamat !!}</td>
                        </tr>
                        <tr>
                            <th>Nomor Telefon</th>
                            <td>{!! $manage_survey->no_tlpn !!}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{!! $manage_survey->email !!}</td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>{{ $manage_survey->description }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Survei Dimulai</th>
                            <td>{{ date("d-m-Y", strtotime($manage_survey->survey_start)) }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Survei Berakhir</th>
                            <td>{{ date("d-m-Y", strtotime($manage_survey->survey_end)) }}</td>
                        </tr>
                        <tr>
                            <th>Klasifikasi Survei</th>
                            <td class="text-primary font-weight-bold">{{ $manage_survey->nama_klasifikasi_survei }}</td>
                        </tr>
                        @if ($manage_survey->nama_jenis_pelayanan_responden != NULL)
                        <tr>
                            <th>Jenis Pelayanan</th>
                            <td>
                                <h5><span
                                        class="badge badge-secondary">{{ $manage_survey->nama_jenis_pelayanan_responden }}</span>
                                </h5>
                            </td>
                        </tr>
                        @endif

                    </table>

                    <!--begin::Separator-->
                    <div class="separator separator-solid my-7"></div>
                    <!--end::Separator-->
                    <!--begin::Bottom-->
                    <div class="d-flex align-items-center flex-wrap">
                        <!--begin: Item-->
                        <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                            <span class="mr-4">
                                <i class="flaticon-network icon-2x text-muted font-weight-bold"></i>
                            </span>
                            <div class="d-flex flex-column text-dark-75">
                                <span class="font-weight-bolder font-size-sm">Metode Sampling</span>
                                <span class="font-weight-bolder font-size-h5">
                                    <span
                                        class="text-dark-50 font-weight-bold"></span>{{ $manage_survey->nama_sampling }}</span>
                            </div>
                        </div>
                        <!--end: Item-->
                        <!--begin: Item-->
                        <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                            <span class="mr-4">
                                <i class="flaticon-file-2 icon-2x text-muted font-weight-bold"></i>
                            </span>
                            <div class="d-flex flex-column text-dark-75">
                                <span class="font-weight-bolder font-size-sm">Jumlah Populasi Yang Diambil</span>
                                <span class="font-weight-bolder font-size-h5">
                                    <span
                                        class="text-dark-50 font-weight-bold"></span>{{ $manage_survey->jumlah_populasi }}</span>
                            </div>
                        </div>
                        <!--end: Item-->
                        <!--begin: Item-->
                        <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                            <span class="mr-4">
                                <i class="flaticon-file-2 icon-2x text-muted font-weight-bold"></i>
                            </span>
                            <div class="d-flex flex-column text-dark-75">
                                <span class="font-weight-bolder font-size-sm">Sample Minimal Wajib Diperoleh</span>
                                <span class="font-weight-bolder font-size-h5">
                                    <span
                                        class="text-dark-50 font-weight-bold"></span>{{ $manage_survey->jumlah_sampling }}</span>
                            </div>
                        </div>
                        <!--end: Item-->
                        <!--begin: Item-->
                        <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                            <span class="mr-4">
                                <i class="flaticon-file-2 icon-2x text-muted font-weight-bold"></i>
                            </span>
                            <div class="d-flex flex-column flex-lg-fill">
                                <span class="text-dark-75 font-weight-bolder font-size-sm">Sample Yang Sudah
                                    Diperoleh</span>
                                <span class="font-weight-bolder font-size-h5">
                                    <span class="text-dark-50 font-weight-bold"></span>{{ $jumlah_kuisioner }}</span>
                            </div>
                        </div>
                        <!--end: Item-->
                        <!--begin: Item-->
                        <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                            <span class="mr-4">
                                <i class="flaticon-file-2 icon-2x text-muted font-weight-bold"></i>
                            </span>
                            <div class="d-flex flex-column">
                                <span class="text-dark-75 font-weight-bolder font-size-sm">Sample Yang Belum
                                    Diperoleh</span>
                                <span class="font-weight-bolder font-size-h5">
                                    <span class="text-dark-50 font-weight-bold"></span>{{ $sampling_belum }}</span>
                            </div>
                        </div>
                        <!--end: Item-->

                    </div>
                    <!--end::Bottom-->


                </div>
            </div>

        </div>
    </div>

</div>

@endsection

@section('javascript')

@endsection
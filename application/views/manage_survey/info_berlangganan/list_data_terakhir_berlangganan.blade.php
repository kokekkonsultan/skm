@php
$ci = get_instance();
@endphp


<div class="card card-custom gutter-b">
    <div class="card-body">

        <div class="d-flex">
            <div class="flex-grow-1">
                <div class="align-items-center justify-content-between">

                    <div class="d-flex align-items-center">
                        <span class="bullet bullet-bar bg-info align-self-stretch"></span>
                        <label class="checkbox checkbox-lg checkbox-light-info checkbox-inline flex-shrink-0 m-0 mx-4">
                            <input type="checkbox" name="select" value="1" disabled="">
                            <span></span>
                        </label>
                        <div class="d-flex flex-column flex-grow-1">
                            <div class="text-dark text-hover-success font-size-h5 font-weight-bold mb-1">
                                {!! $last_payment->nama_paket !!}
                            </div>
                            <span class="text-muted font-weight-bold">
                                {!! $last_payment->deskripsi_paket !!}</span>
                        </div>
                    </div>
                </div>

                <hr>


                <div class="d-flex align-items-center flex-wrap justify-content-between">
                    <div class="d-flex flex-wrap align-items-center py-2">
                        <div class="d-flex align-items-center">
                            <div class="mr-6">
                                <div class="font-weight-bold mb-2">Tanggal Pembelian</div>
                                <span
                                    class="btn btn-sm btn-text btn-light-primary text-uppercase font-weight-bold">{{ date('d-m-Y', strtotime($last_payment->tanggal_mulai)) }}</span>
                            </div>
                            <div class="mr-6">
                                <div class="font-weight-bold mb-2">Lama Berlangganan</div>
                                <span
                                    class="btn btn-sm btn-text btn-light-info text-uppercase font-weight-bold">{{ $last_payment->panjang_hari }}
                                    Hari</span>
                            </div>

                            <!-- <div class="mr-6">
                                <div class="font-weight-bold mb-2">Status Paket</div>
                                {!! $status_paket !!}
                            </div> -->

                            <div class="mr-6">
                                <div class="font-weight-bold mb-2">Jatuh Tempo</div>
                                <span class="btn btn-sm btn-text btn-light-danger text-uppercase font-weight-bold">{!!
                                    $status_jatuh_tempo !!}</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="separator separator-solid my-7"></div>

        <div class="d-flex align-items-center">
            <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                <span class="mr-4">
                    <i class="icon-2x flaticon-price-tag text-muted font-weight-bold"></i>
                </span>
                <div class="d-flex flex-column text-dark-75">
                    <span class="font-weight-bolder font-size-sm">Harga Paket</span>
                    <span class="text-primary font-weight-bolder font-size-h5">
                        <span class="text-dark-50 font-weight-bold"></span>Rp.
                        {{ number_format($last_payment->harga_paket, 0, ',', '.') }} </span>
                </div>
            </div>



            <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                <span class="mr-4">
                    <i class="icon-2x flaticon-users-1 text-muted font-weight-bold"></i>
                </span>
                <div class="d-flex flex-column text-dark-75">
                    <span class="font-weight-bolder font-size-sm">Jumlah User</span>
                    <span class="text-primary font-weight-bolder font-size-h5">
                        <span class="text-dark-50 font-weight-bold"></span>{{ $last_payment->jumlah_user }} User</span>
                </div>
            </div>

            <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                <span class="mr-4">
                    <i class="flaticon-file-2 icon-2x text-muted font-weight-bold"></i>
                </span>
                <div class="d-flex flex-column text-dark-75">
                    <span class="font-weight-bolder font-size-sm">Jumlah Kuesioner</span>
                    <span class="text-primary font-weight-bolder font-size-h5">
                        <span class="text-dark-50 font-weight-bold"></span>{{ $last_payment->jumlah_kuesioner }}
                        Kuesioner</span>
                </div>
            </div>

            <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                <span class="mr-4">
                    <i class="icon-2x flaticon2-check-mark text-muted font-weight-bold"></i>
                </span>
                <div class="d-flex flex-column flex-lg-fill">
                    <span class="text-dark-75 font-weight-bolder font-size-sm">Status Paket</span>
                    <span class="text-primary font-weight-bolder font-size-h5">
                        <span class="text-dark-50 font-weight-bold"></span>{!! $status_paket !!}</span>
                </div>
            </div>

        </div>
    </div>
</div>



















<!-- <div class="card">
    <div class="card-header font-weight-bold">
        Current Subscription
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-md-6">

                <p>
                    <label>Nama Paket</label><br>
                    <span class="font-weight-bold">{{ $last_payment->nama_paket }}</span>
                </p>

                <p>
                    <label>Deskripsi</label><br>
                    <span class="font-weight-bold">{!! $last_payment->deskripsi_paket !!}</span>
                </p>

                <p>
                    <label>Lama Berlangganan</label><br>
                    <span class="font-weight-bold">{{ $last_payment->panjang_hari }} Hari</span>
                </p>

                <p>
                    <label>Harga Paket</label><br>
                    <span class="font-weight-bold">{{ $last_payment->harga_paket }}</span>
                </p>

                <p>
                    <label>Tanggal Pembelian</label><br>
                    <span class="font-weight-bold">{{ date('d-m-Y', strtotime($last_payment->tanggal_mulai)) }}</span>
                </p>

            </div>

            <div class="col-md-6">

                <p>
                    <label>Jumlah User</label><br>
                    <span class="font-weight-bold">{{ $last_payment->jumlah_user }}</span>
                </p>

                <p>
                    <label>Jumlah Kuesioner</label><br>
                    <span class="font-weight-bold">{{ $last_payment->jumlah_kuesioner }}</span>
                </p>

                <p>
                    <label>Status Paket</label><br>
                    <span>{!! $status_paket !!}</span>
                </p>

                <p>
                    <label>Tanggal Jatuh Tempo</label><br>
                    <span class="font-weight-bold text-danger">{!! $status_jatuh_tempo !!}</span>

                </p>

            </div>
        </div>

    </div>
</div> -->
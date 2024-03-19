@php
$ci = get_instance();
@endphp


<div class=" container-fluid">

    <div class="card card-custom">
        <div class="card-body p-0">
            <!--begin::Invoice-->
            <div class="row justify-content-center pt-8 px-8 pt-md-27 px-md-0">
                <div class="col-md-9">

                    <div class="d-flex justify-content-between pb-10 pb-md-20 flex-column flex-md-row">
                        <h1 class="display-4 font-weight-boldest mb-10">INVOICE</h1>
                        <div class="d-flex flex-column align-items-md-end px-0">

                            <a href="#" class="mb-5 max-w-200px">
                                <span class="svg-icon svg-icon-full">
                                    <img src="http://192.168.1.59:8881/assets/themes/chain/assets/images/logo.png"
                                        width="200px" alt="">

                                </span>
                            </a>

                            <span
                                class="d-flex flex-column align-items-md-end font-size-h5 font-weight-bold text-muted">
                                <span>Kota Surabaya, Jawa Timur, </span>
                                <span>Indonesia.</span>
                            </span>
                        </div>
                    </div>
                    <div class="rounded-xl overflow-hidden w-100 max-h-md-250px mb-30">
                        <img src="https://preview.keenthemes.com/metronic/theme/html/demo1/dist/assets/media/bg/bg-invoice-5.jpg"
                            class="w-100" alt="">
                    </div>

                    <div class="row border-bottom pb-10">
                        <div class="col-md-9 py-md-10 pr-md-10">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th
                                                class="pt-1 pb-9 pl-0 font-weight-bolder text-muted font-size-lg text-uppercase">
                                                Deskripsi</th>
                                            <th
                                                class="pt-1 pb-9 text-right font-weight-bolder text-muted font-size-lg text-uppercase">
                                                Jangka Waktu</th>
                                            <th
                                                class="pt-1 pb-9 text-right font-weight-bolder text-muted font-size-lg text-uppercase">
                                                Jumlah User</th>
                                            <th
                                                class="pt-1 pb-9 text-right font-weight-bolder text-muted font-size-lg text-uppercase">
                                                Jumlah Kuesioner</th>
                                            <!-- <th
                                                class="pt-1 pb-9 text-right font-weight-bolder text-muted font-size-lg text-uppercase">
                                                Harga</th> -->

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="font-weight-bolder font-size-lg">
                                            <td class="border-top-0 pl-0 pt-7 d-flex align-items-center">
                                                <span class="navi-icon mr-2">
                                                    <i class="fa fa-genderless text-danger font-size-h2"></i>
                                                </span><?php echo $get_data->nama_paket ?>
                                            </td>
                                            <td class="text-right pt-7"><?php echo $get_data->panjang_hari ?> Hari</td>
                                            <td class="text-right pt-7"><?php echo $get_data->jumlah_user ?></td>
                                            <td class="text-right pt-7"><?php echo $get_data->jumlah_kuesioner ?></td>
                                            <!-- <td class="text-right pt-7"> Rp.
                                                <?php echo number_format($get_data->harga_paket, 0, ',', '.') ?></td> -->

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="border-bottom w-100 mt-7 mb-13"></div>
                            <div class="d-flex flex-column flex-md-row">
                                <div class="d-flex flex-column mb-10 mb-md-0">
                                    <div class="font-weight-bold font-size-h6 mb-3">BANK TRANSFER</div>
                                    <div class="d-flex justify-content-between font-size-lg mb-3">
                                        <span class="font-weight-bold mr-15">Nama Akun:</span>
                                        <span class="text-right"><?php echo $get_data->username ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between font-size-lg mb-3">
                                        <span class="font-weight-bold mr-15">Metode Pembayaran:</span>
                                        <span class="text-right"><?php echo $get_data->nama_metode_pembayaran ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between font-size-lg">
                                        <span class="font-weight-bold mr-15">Kode Pembayaran:</span>
                                        <span class="text-right">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 border-left-md pl-md-10 py-md-10 text-right">
                            <!--begin::Total Amount-->
                            <div class="font-size-h4 font-weight-bolder text-muted mb-3">TOTAL TAGIHAN</div>
                            <div class="font-size-h3 font-weight-boldest"> Rp.
                                <?php echo number_format($get_data->harga_paket, 0, ',', '.') ?></div>
                            <!-- <div class="text-muted font-weight-bold mb-16">Taxes included</div> -->

                            <div class="border-bottom w-100 mb-16"></div>
                            <!--begin::Invoice To-->
                            <div class="text-dark-50 font-size-lg font-weight-bold mb-3">INVOICE TO.</div>
                            <div class="font-size-lg font-weight-bold mb-10"><?php echo $get_data->first_name ?>
                                <br><?php echo $get_data->last_name ?>
                            </div>

                            <div class="text-dark-50 font-size-lg font-weight-bold mb-3">INVOICE NO.</div>
                            <div class="font-size-lg font-weight-bold mb-10"><?php echo $get_data->uuid ?></div>

                            <div class="text-dark-50 font-size-lg font-weight-bold mb-3">DATE</div>
                            <div class="font-size-lg font-weight-bold">
                                <?php echo date("d F Y", strtotime($get_data->tanggal_mulai)) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
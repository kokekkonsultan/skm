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


            @if($manage_survey->organisasi == '' || $manage_survey->jumlah_populasi == '')
            <div class="alert alert-custom alert-notice alert-light-dark fade show" role="alert">
                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                <div class="alert-text">Silahkan lengkapi data terkait survei yang
                    dibuat, guna untuk memudahkan dalam pengambilan data hasil survei!</div>
                <div class="alert-close">
                </div>
            </div>
            @endif


            <div class="card card-custom bgi-no-repeat gutter-b"
                style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/taieri.svg)"
                data-aos="fade-down">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <h3 class="text-white font-weight-bolder line-height-lg mb-5">
                            {{strtoupper($title)}}
                        </h3>

                        @if($manage_survey->organisasi == '' || $manage_survey->description == '' ||
                        $manage_survey->alamat == '' ||
                        $manage_survey->no_tlpn == '' || $manage_survey->email == '' || $manage_survey->jumlah_populasi
                        == '')
                        <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-toggle="modal"
                            data-target=".bd-example-modal-lg"><i class="fas fa-edit"></i> Lengkapi Data Survei</button>

                        @else

                        <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-toggle="modal"
                            data-target=".bd-example-modal-lg"><i class="fas fa-edit"></i> Edit
                            Detail Survei</button>

                        @endif


                        @if ((time() < strtotime($manage_survey->survey_end)))
                            <button type="button" class="btn btn-danger btn-sm font-weight-bold" onclick="close_survey({{$manage_survey->id}})"><i class="fas fa-lock"></i> Tutup Survei</button>
                        @else
                        <button type="button" class="btn btn-success btn-sm font-weight-bold" data-toggle="modal" data-target="#survei_date"><i class="fas fa-unlock"></i> Buka Survei</button>
                        @endif

                    </div>
                </div>
            </div>

            <div class="card card-custom gutter-b">
                <div class="card-body">

                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <div class="align-items-center justify-content-between">

                                <div class="d-flex align-items-center">
                                    <span class="bullet bullet-bar bg-primary align-self-stretch"></span>
                                    <label
                                        class="checkbox checkbox-lg checkbox-light-primary checkbox-inline flex-shrink-0 m-0 mx-4">
                                        <input type="checkbox" name="select" value="1" disabled>
                                        <span></span>
                                    </label>
                                    <div class="d-flex flex-column flex-grow-1">
                                        <div class="text-dark text-hover-primary font-size-h5 font-weight-bold mb-1">
                                            {{ $profiles->survey_name }} <i
                                                class="flaticon2-correct text-primary icon-md ml-2"></i>
                                        </div>
                                        <span class="text-muted font-weight-bold">
                                            <?php if ($manage_survey->organisasi != '') {
                                                echo $manage_survey->organisasi;
                                            } else {
                                                echo 'BELUM DIISI !';
                                            } ?>
                                        </span>
                                    </div>
                                </div>

                                <hr>

                                <div class="d-flex my-2">
                                    <a href="javascript:void(0);"
                                        class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                                        <span class="svg-icon svg-icon-md svg-icon-gray-500 mr-1">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                                viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"></rect>
                                                    <path
                                                        d="M21,12.0829584 C20.6747915,12.0283988 20.3407122,12 20,12 C16.6862915,12 14,14.6862915 14,18 C14,18.3407122 14.0283988,18.6747915 14.0829584,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,12.0829584 Z M18.1444251,7.83964668 L12,11.1481833 L5.85557487,7.83964668 C5.4908718,7.6432681 5.03602525,7.77972206 4.83964668,8.14442513 C4.6432681,8.5091282 4.77972206,8.96397475 5.14442513,9.16035332 L11.6444251,12.6603533 C11.8664074,12.7798822 12.1335926,12.7798822 12.3555749,12.6603533 L18.8555749,9.16035332 C19.2202779,8.96397475 19.3567319,8.5091282 19.1603533,8.14442513 C18.9639747,7.77972206 18.5091282,7.6432681 18.1444251,7.83964668 Z"
                                                        fill="#000000"></path>
                                                    <circle fill="#000000" opacity="0.3" cx="19.5" cy="17.5" r="2.5">
                                                    </circle>
                                                </g>
                                            </svg>
                                        </span>
                                        <?php if ($manage_survey->email != '') {
                                            echo $manage_survey->email;
                                        } else {
                                            echo 'BELUM DIISI !';
                                        } ?>
                                    </a>

                                    <a href="javascript:void(0);"
                                        class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                                        <span class="svg-icon svg-icon-md svg-icon-gray-500 mr-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                                fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 24 19">
                                                <path fill-rule="evenodd"
                                                    d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z" />
                                            </svg>
                                        </span>
                                        <?php if ($manage_survey->no_tlpn != '') {
                                            echo $manage_survey->no_tlpn;
                                        } else {
                                            echo 'BELUM DIISI !';
                                        } ?>
                                    </a>


                                    <a href="javascript:void(0);" class="text-muted text-hover-primary font-weight-bold">
                                        <span class="svg-icon svg-icon-md svg-icon-gray-500 mr-1">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                                viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"></rect>
                                                    <path
                                                        d="M9.82829464,16.6565893 C7.02541569,15.7427556 5,13.1079084 5,10 C5,6.13400675 8.13400675,3 12,3 C15.8659932,3 19,6.13400675 19,10 C19,13.1079084 16.9745843,15.7427556 14.1717054,16.6565893 L12,21 L9.82829464,16.6565893 Z M12,12 C13.1045695,12 14,11.1045695 14,10 C14,8.8954305 13.1045695,8 12,8 C10.8954305,8 10,8.8954305 10,10 C10,11.1045695 10.8954305,12 12,12 Z"
                                                        fill="#000000"></path>
                                                </g>
                                            </svg>
                                        </span>
                                        <?php if ($manage_survey->alamat != '') {
                                            echo $manage_survey->alamat;
                                        } else {
                                            echo 'BELUM DIISI !';
                                        } ?>
                                    </a>
                                </div>
                            </div>

							@if($manage_survey->description != '')	
                            <div class="d-flex align-items-center flex-wrap justify-content-between">
                                <div class="flex-grow-1 font-weight-bold text-dark-50 py-5 py-lg-2 mr-5">
                                    {{ $manage_survey->description }}
                                </div>
                            </div>
							@endif



                            <div class="d-flex align-items-center flex-wrap justify-content-between">
                                <div class="d-flex flex-wrap align-items-center py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-6">
                                            <div class="font-weight-bold mb-2">Survei Dimulai</div>
                                            <button type="button"
                                                class="btn btn-sm btn-text btn-light-primary text-uppercase font-weight-bold"
                                                data-toggle="modal" data-target="#survei_date">
                                                {{ date("d M Y", strtotime($profiles->survey_start)) }}
                                            </button>
                                        </div>
                                        <div class="mr-6">
                                            <div class="font-weight-bold mb-2">Survei Berakhir</div>
                                            <button type="button"
                                                class="btn btn-sm btn-text btn-light-danger text-uppercase font-weight-bold"
                                                data-toggle="modal" data-target="#survei_date">
                                                {{ date("d M Y", strtotime($profiles->survey_end)) }}
                                            </button>
                                        </div>

                                        <div class="mr-6">
                                            <div class="font-weight-bold mb-2">Klasifikasi Survei</div>
                                            <span
                                                class="btn btn-sm btn-text btn-light-info text-uppercase font-weight-bold">{{ $profiles->nama_klasifikasi_survei }}</span>
                                        </div>

                                        @if($profiles->nama_jenis_pelayanan_responden != NULL)
                                        <div class="mr-6">
                                            <div class="font-weight-bold mb-2">Jenis Pelayanan</div>
                                            <span
                                                class="btn btn-sm btn-text btn-light-success text-uppercase font-weight-bold">{{ $profiles->nama_jenis_pelayanan_responden }}</span>
                                        </div>
                                        @endif

                                        <div class="">
                                            <div class="font-weight-bold mb-2">Skala Likert</div>
                                            <span
                                                class="btn btn-sm btn-text btn-light-primary text-uppercase font-weight-bold">
                                                {{$profiles->skala_likert == '' ? 4 : $profiles->skala_likert}} Pilihan
                                                Jawaban</span>
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
                                <i class="flaticon-network icon-2x text-muted font-weight-bold"></i>
                            </span>
                            <div class="d-flex flex-column text-dark-75">
                                <span class="font-weight-bolder font-size-sm">Metode Sampling</span>
                                <span class="text-primary font-weight-bolder font-size-h5">
                                    <span
                                        class="text-dark-50 font-weight-bold"></span>{{ $profiles->nama_sampling }}</span>
                            </div>
                        </div>



                        <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                            <span class="mr-4">
                                <i class="flaticon-file-2 icon-2x text-muted font-weight-bold"></i>
                            </span>
                            <div class="d-flex flex-column text-dark-75">
                                <span class="font-weight-bolder font-size-sm">Jumlah Populasi Yang Diambil</span>
                                <span class="text-primary font-weight-bolder font-size-h5">
                                    <span
                                        class="text-dark-50 font-weight-bold"></span>{{ $profiles->jumlah_populasi }}</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                            <span class="mr-4">
                                <i class="flaticon-file-2 icon-2x text-muted font-weight-bold"></i>
                            </span>
                            <div class="d-flex flex-column text-dark-75">
                                <span class="font-weight-bolder font-size-sm">Sample Minimal Wajib Diperoleh</span>
                                <span class="text-primary font-weight-bolder font-size-h5">
                                    <span
                                        class="text-dark-50 font-weight-bold"></span>{{ $profiles->jumlah_sampling }}</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                            <span class="mr-4">
                                <i class="flaticon-file-2 icon-2x text-muted font-weight-bold"></i>
                            </span>
                            <div class="d-flex flex-column flex-lg-fill">
                                <span class="text-dark-75 font-weight-bolder font-size-sm">Sample Yang Sudah
                                    Diperoleh</span>
                                <span class="text-primary font-weight-bolder font-size-h5">
                                    <span class="text-dark-50 font-weight-bold"></span>{{ $jumlah_kuisioner }}</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                            <span class="mr-4">
                                <i class="flaticon-file-2 icon-2x text-muted font-weight-bold"></i>
                            </span>
                            <div class="d-flex flex-column">
                                <span class="text-dark-75 font-weight-bolder font-size-sm">Sample Yang Belum
                                    Diperoleh</span>
                                <span class="text-primary font-weight-bolder font-size-h5">
                                    <span class="font-weight-bolder"></span>{{ $sampling_belum }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- ============================================== MODAL UBAH TANGGAL SURVEI ======================================================= -->
<div class="modal fade" id="survei_date" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title" id="exampleModalLabel">Periode Survei</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form
                    action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/setting-survei/periode' ?>"
                    class="form_pembuka">
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label font-weight-bold">Tanggal Survey Dibuka <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <input type="date" name="survey_start" value="{{ $manage_survey->survey_start }}"
                                id="survey_start" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row mt-5">
                        <label class="col-sm-5 col-form-label font-weight-bold">Tanggal Survey Ditutup <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <input type="date" name="survey_end" value="{{ $manage_survey->survey_end }}"
                                id="survey_end" class="form-control">
                        </div>
                    </div>
                    <div class="text-right mt-5">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary font-weight-bold tombolSimpanPeriode btn-sm"
                            <?php echo $manage_survey->is_survey_close == 1 ? 'disabled' : '' ?>>Update Periode</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


<!-- ==============================================MODAL EDIT======================================================= -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h5 class="modal-title" id="exampleModalLabel">Lengkapi Data Survei</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<i aria-hidden="true" class="ki ki-close"></i>
					</button>
                </div>
				<form
					action="<?php echo base_url() . $ci->uri->segment(1) . '/' . $ci->uri->segment(2) . '/update-repository' ?>"
					class="form_pembuka">
                <div class="modal-body">


                        <small class="text-danger">
                            <?php echo validation_errors(); ?></small>


							<div class="row">
								<div class="col-md-6">

									<h5 class="text-primary">Data Survei</h5>
                       

										
											<div class="">
												<label for="recipient-name" class="col-form-label font-weight-bold">Nama
													Survei <span style="color:red;">*</span></label>
													<input type="text" class="form-control" name="nama_survei" value="<?php echo $manage_survey->survey_name ?>" required autofocus />
											</div>

											<div class="">
												<label for="recipient-name" class="col-form-label font-weight-bold">Organisasi Yang
													di Survei <span style="color:red;">*</span></label>
													<input type="text" class="form-control" name="organisasi" value="<?php echo $manage_survey->organisasi ?>" required />
												<small>Tuliskan secara lengkap organisasi yang anda survei.</small>
											</div>
										

										<div class="mb-5">
											<label for="recipient-name" class="col-form-label font-weight-bold">Deskripsi Survei</label>
											<textarea class="form-control" name="deskripsi"><?php echo $manage_survey->description ?></textarea>
										</div>


										<div class="row">
											<div class="col-sm-6">
												<label class="col-form-label font-weight-bold">Metode Sampling <span
														class="text-danger">*</span></label>
												@php
												echo form_dropdown($id_sampling);
												@endphp

												<p class=""><a data-toggle="modal" data-target="#myModal" href="#exampleModal">Hitung
														Sampling Disini</a></p>
											</div>

											<div class="col-sm-6">
												<label class="col-form-label font-weight-bold">Jumlah Populasi <span
														class="text-danger">*</span></label>
												<input class="form-control" type="number" name="jumlah_populasi" id="jumlah_populasi"
													value="<?php echo $manage_survey->jumlah_populasi ?>"
													placeholder="Masukkan Jumlah Populasi ..." required>
											</div>
										</div>

								</div>

								<div class="col-md-6">
									<h5 class="text-primary">Data Organisasi</h5>
									
										<div class="">
											<label for="recipient-name" class="col-form-label font-weight-bold">Alamat</label>
											<textarea id="" cols="20" rows="5" class="form-control" name="alamat"><?php echo $manage_survey->alamat ?></textarea>
										</div>
			
										<div class="">
											<label for="recipient-name" class="col-form-label font-weight-bold">Email</label>
											<input type="email" class="form-control" name="email"
												value="<?php echo $manage_survey->email ?>" />
										</div>
			
										<div class="">
											<label for="recipient-name" class="col-form-label font-weight-bold">Telepon</label>
											<input type="number" class="form-control" name="nomor"
												value="<?php echo $manage_survey->no_tlpn ?>" />
										</div>
									
								</div>
							</div>
                        

                        <!-- <div class="form-group row">
                            <div class="col-sm-6">
                                <label class="col-form-label font-weight-bold">Visi Organisasi <span
                                        class="text-danger">*</span></label>
                                <textarea name="visi" id="visi" value="" class="form-control" rows="5"
                                    required>{!! $manage_survey->visi !!}</textarea>
                            </div>

                            <div class="col-sm-6">
                                <label class="col-form-label font-weight-bold">Misi Organisasi <span
                                        class="text-danger">*</span></label>
                                <textarea name="misi" id="misi" value="" class="form-control" rows="5"
                                    required>{!! $manage_survey->misi !!}</textarea>
                            </div>
                        </div> -->

                        
                    

                </div>
				<div class="modal-footer">
					<div class="text-right mt-5">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary font-weight-bold tombolSimpanPembuka"
							onclick="tinyMCE.triggerSave();">Update
							Deskripsi</button>
					</div>
				</div>
			</form>
            </div>
        </div>
    </div>
</div>





<!-- SAMPLING -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Metode Sampling</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                            aria-controls="home" aria-selected="true">Krejcie</a>
                    </li>
                    {{-- <li class="nav-item" role="presentation">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                            aria-controls="profile" aria-selected="false">Cochran</a>
                    </li> --}}
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                            aria-controls="contact" aria-selected="false">Slovin</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                        <br>
                        <h5>Isikan nilai hanya pada bidang berwarna kuning</h5>
                        <br>
                        <form id="formJsKrejcie" name="formJsKrejcie" action="" method="post"
                            enctype="multipart/form-data">
                            <label class="font-weight-bold" style="display:none">lambda:</label>
                            <input type="text" name="lambda" onkeyup="OnChange(this.value)"
                                onKeyPress="return isNumberKey(event)" value="3.841" class="form-control"
                                style="display:none"><br>

                            <label class="font-weight-bold">Masukkan Jumlah Populasi (N:)</label>
                            <input type="number" name="populasi" id="populasi_krejcie" onkeyup="OnChange(this.value)"
                                onKeyPress="return isNumberKey(event)" class="form-control"
                                style="background-color: yellow;"><br>

                            <label class="font-weight-bold" style="display:none">P=Q:</label>
                            <input type="text" name="populasi_menyebar" onkeyup="OnChange(this.value)"
                                onKeyPress="return isNumberKey(event)" value="0.5" class="form-control"
                                style="display:none"><br>

                            <label class="font-weight-bold" style="display:none">d:</label>
                            <input type="text" name="val_d" onkeyup="OnChange(this.value)"
                                onKeyPress="return isNumberKey(event)" value="0.05" class="form-control"
                                style="display:none"><br>

                            <label class="font-weight-bold">Jumlah Minimal Sampling (S:)</label>
                            <input type="text" name="txtDisplay" value="" class="form-control" readonly="readonly"
                                style="background-color: black; color: #FFFFFF;">
                        </form>

                        <br><br>
                        <div class="text-right">
                            <button type="button" class="btn btn-light-primary font-weight-bold shadow-lg"
                                data-dismiss="modal">Batal</button>
                            <button type="" onclick="copytextbox_krejcie()"
                                class="btn btn-primary font-weight-bold shadow-lg">Gunakan</button>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                        <br>
                        <h5>Isikan nilai hanya pada bidang berwarna kuning</h5>
                        <br>
                        <form id="formJsCochran" name="formJsCochran" action="" method="post"
                            enctype="multipart/form-data">
                            <label class="font-weight-bold">Z:</label>
                            <input type="text" name="val_z" onkeyup="OnChangeC(this.value)"
                                onKeyPress="return isNumberKey(event)" value="1.96" class="form-control">

                            <label class="font-weight-bold">p:</label>
                            <input type="text" name="val_p" onkeyup="OnChangeC(this.value)"
                                onKeyPress="return isNumberKey(event)" value="0.5" class="form-control">

                            <label class="font-weight-bold">q:</label>
                            <input type="text" name="val_q" onkeyup="OnChangeC(this.value)"
                                onKeyPress="return isNumberKey(event)" value="0.5" class="form-control">

                            <label class="font-weight-bold">d:</label>
                            <input type="text" name="val_d" onkeyup="OnChangeC(this.value)"
                                onKeyPress="return isNumberKey(event)" class="form-control"
                                style="background-color: yellow;">

                            <label class="font-weight-bold">n:</label>
                            <input type="text" name="txtDisplay" value="" class="form-control" readonly="readonly"
                                style="background-color: black; color: #FFFFFF;">
                        </form>
                    </div>



                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">

                        <br>
                        <h5>Isikan nilai hanya pada bidang berwarna kuning</h5>
                        <br>
                        <form id="formJsSlovin" name="formJsSlovin" action="" method="post"
                            enctype="multipart/form-data">
                            <br>
                            <label class="font-weight-bold">Masukkan Jumlah Populasi (N:)</label>
                            <input type="number" name="val_n" id="val_n" onkeyup="OnChangeS(this.value)"
                                onKeyPress="return isNumberKey(event)" class="form-control"
                                style="background-color: yellow;">

                            <label class="font-weight-bold" style="display:none">e:</label>
                            <input type="text" name="val_e" onkeyup="OnChangeS(this.value)"
                                onKeyPress="return isNumberKey(event)" value="0.05" class="form-control"
                                style="display:none">
                            <br><br>
                            <label class="font-weight-bold">Jumlah Minimal Sampling (n:)</label>
                            <input type="text" name="txtDisplay" value="" class="form-control" readonly="readonly"
                                style="background-color: black; color: #FFFFFF;">
                        </form>

                        <br><br>
                        <div class="text-right">
                            <button type="button" class="btn btn-light-primary font-weight-bold"
                                data-dismiss="modal">Batal</button>
                            <button type="" onclick="copytextbox_slovin()"
                                class="btn btn-primary font-weight-bold">Gunakan</button>
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>
</div>


@endsection

@section('javascript')
<script src="{{ base_url() }}assets/themes/metronic/assets/plugins/custom/tinymce/tinymce.bundle.js"></script>
<script src="{{ base_url() }}assets/themes/metronic/assets/js/pages/crud/forms/editors/tinymce.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>

<script>
var KTTinymce = function() {
    var demos = function() {
        tinymce.init({
            selector: '#visi',
            menubar: false,
            statusbar: false,
            branding: false,
        });
    }
    var demo1 = function() {
        tinymce.init({
            selector: '#misi',
            menubar: false,
            statusbar: false,
            branding: false,
        });
    }
    return {
        init: function() {
            demos();
            demo1();
        }
    };
}();

// Initialization
jQuery(document).ready(function() {
    KTTinymce.init();
});
</script>


<!-- <script>
tinymce.init({
    selector: 'textarea#visi',
    height: 100,
    menubar: false,
    plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table paste code help wordcount'
    ],
    toolbar: 'undo redo | formatselect | ' +
        'bold italic backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | help',
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
});

tinymce.init({
    selector: 'textarea#misi',
    height: 100,
    menubar: false,
    plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table paste code help wordcount'
    ],
    toolbar: 'undo redo | formatselect | ' +
        'bold italic backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | help',
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
});
</script> -->


<script>
function copytextbox_krejcie() {
    document.getElementById('id_sampling').value = 1;
    document.getElementById('jumlah_populasi').value = document.getElementById('populasi_krejcie').value;
    $('#myModal').modal('hide');
}

function copytextbox_slovin() {
    document.getElementById('id_sampling').value = 3;
    document.getElementById('jumlah_populasi').value = document.getElementById('val_n').value;
    $('#myModal').modal('hide');
}
</script>

<script>
dim_lambda = document.formJsKrejcie.lambda.value;
document.formJsKrejcie.txtDisplay.value = dim_lambda;

dim_populasi = document.formJsKrejcie.populasi.value;
document.formJsKrejcie.txtDisplay.value = dim_populasi;

function OnChange(value) {
    dim_lambda = document.formJsKrejcie.lambda.value;
    dim_populasi = document.formJsKrejcie.populasi.value;
    dim_populasi_menyebar = document.formJsKrejcie.populasi_menyebar.value;
    dim_val_d = document.formJsKrejcie.val_d.value;

    total = (dim_lambda * dim_populasi * dim_populasi_menyebar * dim_populasi_menyebar) / ((dim_val_d *
        dim_val_d) * (
        dim_populasi - 1) + (dim_lambda * dim_populasi_menyebar * dim_populasi_menyebar));

    document.formJsKrejcie.txtDisplay.value = Math.ceil(total);
}
</script>

<script>
dim_d = document.formJsCochran.val_d.value;
document.formJsCochran.txtDisplay.value = dim_d;

function OnChangeC(value) {
    dim_val_z = document.formJsCochran.val_z.value;
    dim_val_p = document.formJsCochran.val_p.value;
    dim_val_q = document.formJsCochran.val_q.value;
    dim_val_d = document.formJsCochran.val_d.value;

    total = (((dim_val_z * dim_val_z) * dim_val_p * dim_val_q) / (dim_val_d * dim_val_d));

    document.formJsCochran.txtDisplay.value = Math.ceil(total);
}
</script>

<script>
dim_n = document.formJsSlovin.val_n.value;
document.formJsSlovin.txtDisplay.value = dim_n;

function OnChangeS(value) {
    dim_val_n = document.formJsSlovin.val_n.value;
    dim_val_e = document.formJsSlovin.val_e.value;

    total = dim_val_n / (1 + dim_val_n * (dim_val_e * dim_val_e));

    document.formJsSlovin.txtDisplay.value = Math.ceil(total);
}
</script>

<script>
// ClassicEditor
//     .create(document.querySelector('#visi'))
//     .then(visi => {
//         console.log(visi);
//     })
//     .catch(error => {
//         console.error(error);
//     });

// ClassicEditor
//     .create(document.querySelector('#misi'))
//     .then(misi => {
//         console.log(misi);
//     })
//     .catch(error => {
//         console.error(error);
//     });

$('.form_pembuka').submit(function(e) {
    tinymce.triggerSave();
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolSimpanPembuka').attr('disabled', 'disabled');
            $('.tombolSimpanPembuka').html(
                '<i class="fa fa-spin fa-spinner"></i> Sedang diproses');
            $('.tombolSimpanPeriode').attr('disabled', 'disabled');
            $('.tombolSimpanPeriode').html(
                '<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

            KTApp.block('#content_1', {
                overlayColor: '#000000',
                state: 'primary',
                message: 'Processing...'
            });

            setTimeout(function() {
                KTApp.unblock('#content_1');
            }, 1000);

        },
        complete: function() {
            $('.tombolSimpanPembuka').removeAttr('disabled');
            $('.tombolSimpanPembuka').html('Update Deskripsi');
            $('.tombolSimpanPeriode').removeAttr('disabled');
            $('.tombolSimpanPeriode').html('Update Periode');
        },
        error: function(e) {
            Swal.fire(
                'Error !', e, 'error'
            )
        },
        success: function(data) {
            if (data.validasi) {
                $('.pesan').fadeIn();
                $('.pesan').html(data.validasi);
            }
            if (data.sukses) {
                toastr["success"]('Data berhasil disimpan');
                window.setTimeout(function() {
                    location.reload()
                }, 2000);
            }
        }
    })
    return false;
});
</script>


@if($manage_survey->organisasi == '' || $manage_survey->jumlah_populasi == '')
<script type="text/javascript">
$(document).ready(function() {
    Swal.fire({
        icon: 'info',
        title: 'Informasi',
        text: 'Silahkan Lengkapi Data Survei Anda !',
        confirmButtonColor: '#8950FC',
        confirmButtonText: 'Baik, saya mengerti',
    })
});
</script>
@endif


<script>
    function close_survey(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Anda akan menutup survei ini ?",
        type: 'warning',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oke',
        cancelButtonText: 'Batal',

    }).then((result) => {
        if (result.value) {

            if (id == '') {
                
                Swal.fire(
                    'Gagal!',
                    'Tidak dapat menutup survei',
                    'error'
                    );

            } else {

            $.ajax({
                url: "{{ base_url() }}manage-survey/ajax-close-survey/" + id,
                type: "POST",
                dataType: "JSON",
                success: function(data) {

                    Swal.fire(
                    'Closed!',
                    'Data berhasil ditutup',
                    'success'
                    );

                    setTimeout(function() {
                        window.location.href = ('{{ base_url() }}{{ $ci->uri->segment(1) }}/{{ $ci->uri->segment(2) }}/do');
                    }, 2000);

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });

            }


        }
    })
}
</script>
@endsection

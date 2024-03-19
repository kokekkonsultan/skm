<!-- ======================================= TAMBAH PERTANYAAN TAMBAHAN ========================================== -->
<div class="modal fade" id="tambah3" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border border-warning">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Pertanyaan Tambahan</h5>
            </div>
            <div class="modal-body">

                <form
                    action="{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/form-survei/add-pertanyaan-tambahan'}}"
                    class="form_default" method="POST">

                    <div class="row text-center mb-5">
                        <div class="col-md-6">
                            <label>
                                <input type="radio" name="jenis_pertanyaan_tambahan" class="jenis_pertanyaan_tambahan"
                                    value="1" required>
                                <div class="card card-menu">
                                    <div class="card-body">
                                        <div class="text-center font-weight-bold">
                                            <i class="fas fa-plus"></i><br>Melekat Pada Unsur Pelayanan
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div class="col-md-6">
                            <label>
                                <input type="radio" name="jenis_pertanyaan_tambahan" class="jenis_pertanyaan_tambahan"
                                    value="2" required>
                                <div class="card card-menu">
                                    <div class="card-body">
                                        <div class="text-center font-weight-bold">
                                            <i class="fas fa-plus"></i><br>Tidak Melekat Pada Unsur Pelayanan
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div id="melekat_pada_unsur" style="display: none;">
                        <div class="mt-10 mb-10" style="border-bottom:3px dashed #dedede;"></div>
                        <div class="form-group row">
                            @php
                            echo form_label('Unsur Pelayanan Dari <span style="color:red;">*</span>', '',
                            ['class' =>
                            'col-sm-3
                            col-form-label
                            font-weight-bold']);
                            @endphp
                            <div class="col-sm-9">
                                @php
                                echo form_dropdown($id_unsur_pelayanan);
                                @endphp
                            </div>
                        </div>
                    </div>

                    <div id="tidak_melekat_pada_unsur" style="display: none;">
                        <div class="mt-10 mb-10" style="border-bottom:3px dashed #dedede;"></div>
                        <div class="form-group row mt-5">
                            <label class="col-sm-3 col-form-label font-weight-bold">Letak Pertanyaan <span
                                    style="color: red;">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="is_letak_pertanyaan_tambahan"
                                    id="is_letak_pertanyaan_tambahan" required>
                                    <option value="">Please Select</option>
                                    <option value="1">Paling Awal</option>
                                    <option value="2">Paling Akhir</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div id="pertanyaan_lainnya" style="display: none;">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-bold">Nama Pertanyaan <span
                                    style="color:red;">*</span></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend"><span
                                            class="input-group-text font-weight-bold">T<?php echo $jumlah_tambahan ?></span>
                                    </div>
                                    @php
                                    echo form_input($nama_pertanyaan_terbuka);
                                    @endphp
                                </div>
                            </div>
                        </div>



                        <div class="form-group row">
                            @php
                            echo form_label('Isi Pertanyaan Tambahan <span style="color:red;">*</span>', '', ['class' =>
                            'col-sm-3 col-form-label
                            font-weight-bold']);
                            @endphp
                            <div class="col-sm-9">
                                @php
                                echo form_textarea($isi_pertanyaan_terbuka);
                                @endphp
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-bold">Pilihan Jawaban <span
                                    style="color:red;">*</span></label>
                            <div class="col-sm-9">
                                <label>
                                    <input type="radio" name="jenis_jawaban" value="2" class="pilihan" required>
                                    Jawaban Singkat
                                </label>
                                <hr>
                                <label>
                                    <input type="radio" name="jenis_jawaban" value="1" class="pilihan">
                                    Dengan Pilihan Ganda
                                </label>
                            </div>
                        </div>


                        <div name="opsi_1" id="opsi_1" style="display:none">
                            <div class="form-group fieldGroup">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="pilihan_jawaban[]" class="form-control"
                                            placeholder="Masukkan Pilihan Jawaban . . .">
                                    </div>
                                    <div class="input-group-addon col-sm-1">
                                        <a href="javascript:void(0)" class="btn btn-light-success addMore"><i
                                                class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group fieldGroupCopy">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="pilihan_jawaban[]" class="form-control"
                                            placeholder="Masukkan Pilihan Jawaban . . .">
                                    </div>
                                    <div class="input-group-addon col-sm-1">
                                        <a href="javascript:void(0)" class="btn btn-light-danger remove"><i
                                                class="fas fa-trash"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label fw-bold font-weight-bold">Dengan Isian Lainnya
                                    <span style="color:red;">*</span></label>
                                <div class="col-sm-9">
                                    <label>
                                        <input type="radio" name="opsi_pilihan_jawaban" value="1"> Ya
                                    </label>
                                    <hr>
                                    <label>
                                        <input type="radio" name="opsi_pilihan_jawaban" value="2"> Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary tombolDefault">Simpan</button>
                        </div>
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>
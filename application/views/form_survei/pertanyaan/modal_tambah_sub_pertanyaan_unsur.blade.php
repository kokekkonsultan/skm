<!-- ======================================= TAMBAH SUB PERTANYAAN UNSUR ============================================ -->
<div class="modal fade" id="tambah2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border border-warning">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Pertanyaan Sub Unsur</h5>
            </div>
            <div class="modal-body">

                <form
                    action="{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/form-survei/add-pertanyaan-sub-unsur'}}"
                    class="form_default" method="POST">

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label
                        font-weight-bold">Sub Unsur Dari <span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            @php
                            echo form_dropdown($id_parent);
                            @endphp
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label
                        font-weight-bold">Nama Unsur Pelayanan <span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            @php
                            echo form_input($nama_unsur_pelayanan);
                            @endphp
                            <small>
                                Menurut Permenpan dan RB, unsur SKM terbagi 9 unsur antara lain: 1) Persyaratan 2)
                                Sistem, Mekanisme, dan Prosedur 3) Waktu Penyelesaian 4) Biaya/Tarif 5) Produk
                                Spesifikasi Jenis Pelayanan 6) Kompetensi Pelaksana 7) Perilaku Pelaksana 8)
                                Penanganan
                                Pengaduan, Saran dan Masukan 9) Sarana dan prasarana
                            </small>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Pertanyaan Unsur <span
                                style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            @php
                            echo form_textarea($isi_pertanyaan_unsur);
                            @endphp
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Pilihan Jawaban <span
                                style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <div>
                                <label><input type="radio" name="jenis_pilihan_jawaban" id="jenis_pilihan_jawaban"
                                        value="1" class="jawaban" required>
                                    &nbsp 2
                                    Pilihan
                                    Jawaban</label><br>
                            </div>
                            <div>
                                <label><input type="radio" name="jenis_pilihan_jawaban" id="jenis_pilihan_jawaban"
                                        value="2" class="jawaban">
                                    &nbsp 4
                                    Pilihan
                                    Jawaban</label><br>
                            </div>
                        </div>
                    </div>

                    <!-- PILIHAN JAWABAN 2 -->
                    <div name="2_jawaban" class="2_jawaban" style="display:none">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Pilihan Jawaban 1 <span
                                    style="color: red;">*</span></label>
                            <div class="col-sm-9">
                                @php
                                echo form_input($pilihan_jawaban_1);
                                @endphp
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Pilihan Jawaban 2 <span
                                    style="color: red;">*</span></label>
                            <div class="col-sm-9">
                                @php
                                echo form_input($pilihan_jawaban_2);
                                @endphp
                            </div>
                        </div>
                    </div>

                    <!-- PILIHAN JAWABAN 4 -->
                    <div class="4_jawaban" name="4_jawaban" style="display:none">
                        <datalist id="data_jawaban">
                            <?php
                            foreach ($pilihan->result() as $d) {
                                echo "<option value='$d->id'>$d->pilihan_1</option>";
                            }
                            ?>
                        </datalist>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Pilihan Jawaban 1 <span
                                    style="color: red;">*</span></label>
                            <div class="col-sm-9">
                                <input class="form-control pilihan" list="data_jawaban" type="text"
                                    name="pilihan_jawaban[]" id="pilihan_5"
                                    placeholder="Masukkan pilihan jawaban anda .." onchange="return autofill_new();"
                                    autofocus autocomplete='off'>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Pilihan Jawaban 2 <span
                                    style="color: red;">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control pilihan" name="pilihan_jawaban[]" id="pilihan_6">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Pilihan Jawaban 3 <span
                                    style="color: red;">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control pilihan" name="pilihan_jawaban[]" id="pilihan_7">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Pilihan Jawaban 4 <span
                                    style="color: red;">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control pilihan" name="pilihan_jawaban[]" id="pilihan_8">
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary tombolDefault">Simpan</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
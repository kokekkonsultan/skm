<div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border border-warning">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Pertanyaan Kualitatif</h5>
            </div>
            <div class="modal-body">

                <form
                    action="{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/form-survei/add-pertanyaan-kualitatif'}}"
                    class="form_default" method="POST">

                    <div class="form-group row mb-5 mt-5">
                        <label class="col-sm-3 col-form-label
                        font-weight-bold">Isi Pertanyaan <span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" type="text" name="isi_pertanyaan" id="isi_pertanyaan"
                                rows="4" placeholder="Isikan Pertanyaan Kualitatif ..."></textarea>
                        </div>
                    </div>

                    <div class="form-group row mb-5 mt-5">
                        <label class="col-sm-3 col-form-label
                        font-weight-bold">Status Pertanyaan <span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-control" id="is_active" name="is_active" required>
                                <option value=''>Please Select</option>
                                <option value='1'>Aktif</option>
                                <option value='2'>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-right mt-5">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary tombolDefault">Simpan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
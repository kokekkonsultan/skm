@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-secondary font-weight-bold">
            {{ $title }}
        </div>
        <div class="card-body">
            @php
            echo form_open($form_action);
            @endphp
            @php
            echo validation_errors();
            @endphp


            <div class="form-group row">
                @php
                echo form_label('<b>Unsur Pelayanan Dari</b> <span style="color: red;">*</span>', '', ['class' =>
                'col-sm-2 col-form-label']);
                @endphp
                <div class="col-sm-10">
                    @php
                    echo form_input($id_unsur_pelayanan);
                    @endphp
                </div>
            </div>

            <div class="form-group row">
                @php
                echo form_label('<b>Nomor Pertanyaan Tambahan</b> <span style="color: red;">*</span>', '', ['class' =>
                'col-sm-2 col-form-label']);
                @endphp
                <div class="col-sm-10">
                    @php
                    echo form_input($nomor_pertanyaan_terbuka);
                    @endphp
                </div>
            </div>

            <div class="form-group row">
                @php
                echo form_label('<b>Pertanyaan Tambahan</b> <span style="color: red;">*</span>', '', ['class' =>
                'col-sm-2 col-form-label']);
                @endphp
                <div class="col-sm-10">
                    @php
                    echo form_input($nama_pertanyaan_terbuka);
                    @endphp
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label"><b>Isi Pertanyaan</b> <span style="color: red;">*</span></label>
                <div class="col-sm-10">
                    <textarea type="text" name="isi_pertanyaan_terbuka"
                        class="form-control"><?= $perincian_pertanyaan_terbuka->isi_pertanyaan_terbuka ?></textarea><br>
                </div>
            </div>

            <input type="text" name="id_jenis_jawaban"
                value="<?php echo $perincian_pertanyaan_terbuka->id_jenis_pilihan_jawaban; ?>" hidden>

            <hr>
            <br>

            <?php
            $no = 1;
            foreach ($pilihan_jawaban as $row) {
            ?>
            <input type="hidden" class="form-control" id="id_kategori" name="id_kategori[]"
                value="<?php echo $row->id_isi_pertanyaan_ganda; ?>">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label"><b>Pilihan Jawaban <?php echo $no++; ?></b> <span
                        style="color: red;">*</span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="pertanyaan_ganda" name="pertanyaan_ganda[]"
                        value="<?php echo $row->pertanyaan_ganda; ?>">
                </div>
            </div>
            <?php
            }
            ?>

            <div class="text-right mt-3 mb-3">
                @php
                echo anchor(base_url().'pertanyaan-terbuka', 'Batal', ['class'=>'btn btn-light-primary
                font-weight-bold']);
                @endphp
                <button type="submit" name="submit" value="simpan"
                    class="btn btn-primary font-weight-bold">Simpan</button>
            </div>

            @php
            echo form_close();
            @endphp

        </div>
    </div>
</div>
@endsection

@section('javascript')

@endsection
@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')
<div class="container">
    <div class="card" data-aos="fade-down">
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

            {{-- <div class="form-group row">
                @php
                echo form_label('<b>Unsur Pelayanan</b> <span style="color: red;">*</span>', '', ['class' => 'col-sm-2
                col-form-label']);
                @endphp
                <div class="col-sm-10">
                    @php
                    echo form_input($id_unsur_pelayanan);
                    @endphp
                </div>
            </div> --}}


            <div class="form-group row">
                {!! form_label('<b>Unsur Pelayanan</b> <span style="color: red;">*</span>', '', ['class' => 'col-sm-2 col-form-label']) !!}
                <div class="col-sm-10">
                    
                    <div class="input-group">
                     <div class="input-group-prepend"><span class="input-group-text">{{ $nomor_unsur }}</span></div>
                     {!! form_input($nama_unsur_pelayanan) !!}
                    </div>
                    
                </div>
            </div>

            <div class="form-group row">
                @php
                echo form_label('<b>Isi Pertanyaan Unsur</b> <span style="color: red;">*</span>', '', ['class' =>
                'col-sm-2 col-form-label']);
                @endphp
                <div class="col-sm-10">
                    @php
                    echo form_textarea($isi_pertanyaan_unsur);
                    @endphp
                </div>
            </div>


            <h4>Pilihan Jawaban</h4>
            <hr>

                <?php
                $no = 1;
                foreach ($nama_kategori_unsur as $row) {
                ?>
                <input type="text" class="form-control" id="id_kategori" name="id_kategori[]"
                    value="<?php echo $row->id_kategori_unsur_pelayanan; ?>" hidden>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Pilihan Jawaban <?php echo $no++; ?></b> <span
                            style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nama_kategori_unsur_pelayanan"
                            name="nama_kategori_unsur_pelayanan[]"
                            value="<?php echo $row->nama_kategori_unsur_pelayanan; ?>">
                    </div>
                </div>
                <?php
                }
                ?>

            <div class="text-right mt-3 mb-3">
                @php
                echo anchor($ci->session->userdata('urlback_second'), 'Batal', ['class'=>'btn btn-light-primary font-weight-bold shadow']);
                @endphp
                <button type="submit" name="submit" value="simpan" class="btn btn-primary font-weight-bold shadow">Simpan</button>
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
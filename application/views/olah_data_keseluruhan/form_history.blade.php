@extends('include_backend/template_backend')
@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
    type="text/css" />
@endsection

@section('content')
<div class=" container-fluid">
    <div class="card card-custom bgi-no-repeat gutter-b aos-init aos-animate"
        style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/rhone-2.svg)"
        data-aos="fade-down">
        <div class="card-body d-flex align-items-center">
            <div>
                <h3 class="text-white font-weight-bolder line-height-lg mb-5">
                    {{strtoupper($title)}}
                </h3>
            </div>
        </div>
    </div>


    <div class="card shadow aos-init aos-animate" data-aos="fade-up">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover example" style="width:100%">
                    <thead class="bg-gray-300">
                        <tr>
                            <th>No</th>
                            <th>Label</th>
                            <th>Nilai Indeks</th>
                            <th>Survei yang dijadikan Obyek</th>
                            <th>Dibuat</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $no = 1;
                        @endphp
                        @foreach($nilai_induk->result() as $row)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{$row->label}}</td>
                            <td class="text-success"><b>{{ROUND($row->nilai_indeks,3)}}</b></td>
                            <td>
                                @php
                                $id_object = implode(", ", unserialize($row->id_object_indeks));
                                @endphp
                                @foreach($ci->db->query("SELECT * FROM manage_survey WHERE id IN
                                ($id_object)")->result() as $value)

                                @php
                                if ($ci->db->get_where("survey_$value->table_identity", array('is_submit' =>
                                1))->num_rows() > 0) {

                                $nilai_per_unsur = $ci->db->query("SELECT IF(id_parent =
                                0,unsur_pelayanan_$value->table_identity.id,
                                unsur_pelayanan_$value->table_identity.id_parent) AS id_sub,

                                ((SUM(skor_jawaban)/COUNT(DISTINCT
                                survey_$value->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT
                                survey_$value->table_identity.id_responden))) AS nilai_per_unsur,
                                (((SUM(skor_jawaban)/COUNT(DISTINCT
                                survey_$value->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT
                                survey_$value->table_identity.id_responden)))/(SELECT COUNT(id) FROM
                                unsur_pelayanan_$value->table_identity WHERE id_parent = 0)) AS rata_rata_bobot

                                FROM jawaban_pertanyaan_unsur_$value->table_identity

                                JOIN pertanyaan_unsur_pelayanan_$value->table_identity ON
                                jawaban_pertanyaan_unsur_$value->table_identity.id_pertanyaan_unsur =
                                pertanyaan_unsur_pelayanan_$value->table_identity.id

                                JOIN unsur_pelayanan_$value->table_identity ON
                                pertanyaan_unsur_pelayanan_$value->table_identity.id_unsur_pelayanan =
                                unsur_pelayanan_$value->table_identity.id

                                JOIN survey_$value->table_identity ON
                                jawaban_pertanyaan_unsur_$value->table_identity.id_responden =
                                survey_$value->table_identity.id_responden

                                WHERE survey_$value->table_identity.is_submit = 1 AND jawaban_pertanyaan_unsur_$value->table_identity.skor_jawaban != '0.0'
                                GROUP BY id_sub");


                                $nilai_bobot = [];
                                foreach ($nilai_per_unsur->result() as $obj) {
                                $nilai_bobot[] = $obj->rata_rata_bobot;
                                $nilai_tertimbang = array_sum($nilai_bobot);
                                }
                                $nilai = $nilai_tertimbang;
                                } else {
                                $nilai = 0;
                                };
                                @endphp
                                <li>{{$value->survey_name}} <b>({{$value->organisasi}})</b> - <b
                                        class="text-primary">{{ROUND($nilai, 3)}}</b></li>
                                @endforeach
                            </td>
                            <td>{{$row->created_at}}</td>
                            <td>
                                <a class="btn btn-light-primary btn-sm" data-toggle="modal"
                                    data-target="#edit_{{$row->id}}">
                                    <i class="fa fa-edit"></i> Edit</a>
                                <a class="btn btn-light-primary btn-sm font-weight-bold shadow"
                                    href="javascript:void(0)" title="Hapus {{$row->label}}"
                                    onclick="delete_data({{$row->id}})"><i class="fa fa-trash"></i> Delete</a>





                                <!-- ============================================ MODAL EDIT =================================================== -->

                                <div class="modal fade" id="edit_{{$row->id}}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content border border-secondary">
                                            <div class="modal-header bg-secondary">
                                                <h6 class="modal-title" id="exampleModalLabel">Edit {{$row->label}}</h6>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form_default"
                                                    action="<?php echo base_url() . 'olah-data-keseluruhan/edit' ?>"
                                                    method="POST">
                                                    <input name="id_nilai" value="{{$row->id}}" hidden>
                                                    <div class="form-group">
                                                        <label class="form-label font-weight-bold">Masukkan Nama Label
                                                            Indeks <span class="text-danger">*</span></label>
                                                        <input class="form-control" name="label"
                                                            placeholder="Survei Mahkamah Konstitusi Tahun 2023"
                                                            value="{{$row->label}}" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label font-weight-bold">Pilih survei yang
                                                            dijadikan indeks <span class="text-danger">*</span>
                                                        </label>


                                                        <div class="checkbox-list">
                                                        @php
                                                        $parent_obj = unserialize($row->id_object_indeks);
                                                        @endphp
                                                        @foreach($manage_survey->result() as $get)
                                                            <label class="checkbox">
                                                                <table class="table table-bordered">
                                                                    <tr>
                                                                        <td width="5%">
                                                                            <input type="checkbox" name="id[]" value="{{$get->id}}"
                                                                                <?php echo in_array($get->id, $parent_obj) ? 'checked' : '' ?>><span></span>
                                                                        </td>
                                                                        <td width="70%">{{$get->survey_name}}</td>
                                                                        <td width="35%"><b>{{$get->first_name . '
                                                                            ' .  $get->last_name}}</b></td>
                                                                    </tr>
                                                                </table>
                                                            </label>
                                                        
                                                        @endforeach
                                                        </div>
                                                    </div>


                                                    <div class="text-right mt-5">
                                                        <button type="button" class="btn btn-secondary btn-sm"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit"
                                                            class="btn btn-primary btn-sm font-weight-bold tombolDefault">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection



@section('javascript')
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>
<script src="{{ base_url() }}assets/themes/metronic/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script>
$(document).ready(function() {
    $('.example').DataTable();
});


function delete_data(id) {
    if (confirm('Are you sure delete this data?')) {
        $.ajax({
            url: "<?php echo base_url() . 'history-nilai-per-periode/delete/' ?>" + id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    Swal.fire(
                        'Informasi',
                        'Berhasil menghapus data',
                        'success'
                    );
                    window.setTimeout(function() {
                        location.reload()
                    }, 2000);
                } else {
                    Swal.fire(
                        'Informasi',
                        'Hak akses terbatasi. Bukan akun administrator.',
                        'warning'
                    );
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error deleting data');
            }
        });
    }
}
</script>


<script>
$('.form_default').submit(function(e) {
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolDefault').attr('disabled', 'disabled');
            $('.tombolDefault').html(
                '<i class="fa fa-spin fa-spinner"></i> Sedang diproses');
            Swal.fire({
                title: 'Memproses data',
                html: 'Mohon tunggu sebentar. Sistem sedang melakukan request anda.',
                allowOutsideClick: false,
                onOpen: () => {
                    swal.showLoading()
                }
            });
        },

        complete: function() {
            $('.tombolDefault').removeAttr('disabled');
            $('.tombolDefault').html('Simpan');
        },

        error: function(e) {
            Swal.fire(
                'Informasi',
                'Gagal memproses data!',
                'error'
            );
            window.setTimeout(function() {
                location.reload()
            }, 2000);
        },

        success: function(data) {
            // if (data.gagal) {
            //     Swal.fire(
            //         'Informasi',
            //         'Gagal memproses data!',
            //         'error'
            //     );
            // }
            if (data.sukses) {
                $('#checkAll').prop('checked', false);
                Swal.fire(
                    'Informasi',
                    'Berhasil mendapatkan nilai indeks',
                    'success'
                );
                window.setTimeout(function() {
                    location.reload()
                }, 2000);
            }
        }
    });
    return false;
});
</script>
@endsection
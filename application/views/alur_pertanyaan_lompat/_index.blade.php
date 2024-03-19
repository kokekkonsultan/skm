@extends('setting_form_survei/_include/_template')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet">
<link href="{{ base_url() }}assets/vendor/bootstrap/css/bootstrap-colorpicker.css" rel="stylesheet" type="text/css" />
<script src="https://cdn.jsdelivr.net/npm/@jaames/iro@5"></script>
@endsection

@section('content')
<div class="secondary-label">
    {{$title}}
</div>


<div class="alert alert-secondary mb-5" role="alert">
    <i class="flaticon-exclamation-1"></i> Halaman ini digunakan untuk mendifinisikan alur pengisian setiap pertanyaan
    survei, sehingga jika ada pertanyaan yang ingin di lewati dalam kondisi tertentu anda bisa mengaturnya.
</div>


<a class="btn btn-primary font-weight-bold btn-block" href="{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-unsur'}}"><i class="fa fa-arrow-left"></i> Kembali</a>

<!-- <div id="tes"></div> -->



@endsection

@section('form_preview')



<!----------------------------------------------------------------------------------------------------------------------------->
<!----------------------------------------------------- START TERBUKA ATAS ---------------------------------------------------->
<!----------------------------------------------------------------------------------------------------------------------------->

@foreach($ci->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian
FROM pertanyaan_terbuka_$table_identity
JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id =
perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
WHERE is_letak_pertanyaan = 1")->result() as $pt_a)

@php
$model_ta = $pt_a->is_model_pilihan_ganda == 2 ? 'checkbox' : 'radio';
@endphp

<div class="card  shadow mb-5" style="border-left: 5px solid #cefc03;">
    <!-- <div class="text-center mt-2">
        <div class="dropdown">
            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-bars"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" data-toggle="modal" data-target="#edit">Edit</a>

                <a class="dropdown-item" href="javascript:void(0)" title="Hapus" onclick="tes()">Hapus</a>
            </div>
        </div>
    </div> -->


    <div class="card-body">


        <table class="table table-bordered" style="font-size: 14px;">
            <tr>
                <th width="4%">{{$pt_a->nomor_pertanyaan_terbuka}}.</th>
                <th colspan="2">{!! $pt_a->isi_pertanyaan_terbuka !!}</th>
            </tr>


            @if($pt_a->id_jenis_pilihan_jawaban == 1)
            @foreach($ci->db->query("SELECT * FROM isi_pertanyaan_ganda_$table_identity WHERE
            id_perincian_pertanyaan_terbuka = $pt_a->id_perincian")->result() as $ipg_a)
            <tr>
                <td></td>
                <td>
                    <div class="{{$model_ta}}-inline" style="font-size: 14px;">
                        <label class="{{$model_ta}} {{$model_ta}}-outline {{$model_ta}}-success">
                            <input type="{{$model_ta}}" name="{{$pt_a->nomor_pertanyaan_terbuka}}"><span></span>
                            {{$ipg_a->pertanyaan_ganda}}
                        </label>
                    </div>
                </td>

                @if($pt_a->is_model_pilihan_ganda == 1)
                <td width="35%">
                    <select class="form-control form-control-sm" name="is_next_step[]" id="idt_{{$ipg_a->id}}">
                        @php
                        $terbuka_a = $ci->db->query("SELECT * FROM pertanyaan_terbuka_$table_identity
                        WHERE is_letak_pertanyaan = 1 && SUBSTR(nomor_pertanyaan_terbuka,2) >
                        SUBSTR('$pt_a->nomor_pertanyaan_terbuka', 2)");

                        $last_row_a = $terbuka_a->last_row();
                        $number_next = substr($last_row_a->nomor_pertanyaan_terbuka, 1) + 1;
                        @endphp

                        @foreach($terbuka_a->result() as $subpt_a)
                        <option value="{{$subpt_a->nomor_pertanyaan_terbuka}}"
                            <?php echo $ipg_a->is_next_step == $subpt_a->nomor_pertanyaan_terbuka ? 'selected' : '' ?>>
                            Lanjutkan
                            Ke {{$subpt_a->nomor_pertanyaan_terbuka}}</option>
                        @endforeach

                        <option value="T{{$number_next}}"
                            <?php echo $ipg_a->is_next_step == 'T' . $number_next ? 'selected' : '' ?>>Lanjutkan Ke
                            Pertanyaan Unsur Berikutnya
                        </option>

                    </select>
                </td>
                @endif

            </tr>
            @endforeach

            @else
            <tr>
                <td></td>
                <td>
                    <textarea class="form-control" placeholder="Masukkan jawaban anda..."></textarea>
                </td>
            </tr>
            @endif
        </table>
    </div>
</div>

@endforeach

<!----------------------------------------------------------------------------------------------------------------------------->
<!----------------------------------------------------- END TERBUKA ATAS ------------------------------------------------------>
<!----------------------------------------------------------------------------------------------------------------------------->





<!----------------------------------------------------------------------------------------------------------------------------->
<!------------------------------------------------------- START UNSUR --------------------------------------------------------->
<!----------------------------------------------------------------------------------------------------------------------------->

@foreach($pertanyaan_unsur->result() as $row)

<div class="card card-body mb-5 mt-5" style="border-left: 5px solid #0091ff;">
    <table class="table table-bordered" style="font-size: 14px;">
        <tr>
            <th width="4%">{{$row->nomor_unsur}}.</th>
            <th colspan="2">{!! $row->isi_pertanyaan_unsur !!}
            </th>
        </tr>

        @foreach($ci->db->query("SELECT * FROM kategori_unsur_pelayanan_$table_identity WHERE id_unsur_pelayanan =
        $row->id_unsur_pelayanan")->result() as $value)
        <tr>
            <td></td>
            <td>
                <div class="radio-inline" style="font-size: 14px;">
                    <label class="radio radio-outline radio-success">
                        <input type="radio" name="{{$row->nomor_unsur}}"><span></span>
                        {{$value->nama_kategori_unsur_pelayanan}}
                    </label>
                </div>
            </td>

            <td width="35%">
                <select class="form-control form-control-sm" name="is_next_step[]" id="idu_{{$value->id}}">
                    @php
                    $pertanyaan_terbuka = $ci->db->get_where("pertanyaan_terbuka_$table_identity",
                    array('id_unsur_pelayanan' => $row->id_unsur_pelayanan));

                    $last_row = $pertanyaan_terbuka->last_row();
                    $number_next = substr($last_row->nomor_pertanyaan_terbuka, 1) + 1;
                    @endphp

                    @foreach($pertanyaan_terbuka->result() as $get)
                    <option value="{{$get->nomor_pertanyaan_terbuka}}"
                        <?php echo $value->is_next_step == $get->nomor_pertanyaan_terbuka ? 'selected' : '' ?>>Lanjutkan
                        Ke {{$get->nomor_pertanyaan_terbuka}}</option>
                    @endforeach

                    <option value="T{{$number_next}}"
                        <?php echo $value->is_next_step == 'T' . $number_next ? 'selected' : '' ?>>Lanjutkan Ke
                        Pertanyaan Unsur Berikutnya</option>
                </select>
            </td>
        </tr>
        @endforeach
    </table>
</div>


<!----------------------------------------------------------------------------------------------------------------------------->
<!----------------------------------------------------- START TERBUKA --------------------------------------------------------->
<!----------------------------------------------------------------------------------------------------------------------------->

@foreach($ci->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian
FROM pertanyaan_terbuka_$table_identity
JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id =
perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
WHERE id_unsur_pelayanan = $row->id_unsur_pelayanan")->result() as $pt)

@php
$model_t = $pt->is_model_pilihan_ganda == 2 ? 'checkbox' : 'radio';
@endphp


<div class="card card-body shadow mb-5" style="border-left: 5px solid #bb00ff;">
    <table class="table table-bordered" style="font-size: 14px;">
        <tr>
            <th width="4%">{{$pt->nomor_pertanyaan_terbuka}}.</th>
            <th colspan="2">{!! $pt->isi_pertanyaan_terbuka !!}</th>
        </tr>


        @if($pt->id_jenis_pilihan_jawaban == 1)
        @foreach($ci->db->query("SELECT * FROM isi_pertanyaan_ganda_$table_identity WHERE
        id_perincian_pertanyaan_terbuka = $pt->id_perincian")->result() as $ipg)
        <tr>
            <td></td>
            <td>
                <div class="{{$model_t}}-inline" style="font-size: 14px;">
                    <label class="{{$model_t}} {{$model_t}}-outline {{$model_t}}-success">
                        <input type="{{$model_t}}" name="{{$pt->nomor_pertanyaan_terbuka}}"><span></span>
                        {{$ipg->pertanyaan_ganda}}
                    </label>
                </div>
            </td>


            @if($pt->is_model_pilihan_ganda == 1)
            <td width="35%">
                <select class="form-control form-control-sm" name="is_next_step[]" id="idt_{{$ipg->id}}">
                    @php
                    $terbuka_u = $ci->db->query("SELECT * FROM pertanyaan_terbuka_$table_identity
                    WHERE id_unsur_pelayanan = $row->id_unsur_pelayanan && SUBSTR(nomor_pertanyaan_terbuka,2) >
                    SUBSTR('$pt->nomor_pertanyaan_terbuka', 2)");

                    $last_row = $terbuka_u->last_row();
                    $number_next = substr($last_row->nomor_pertanyaan_terbuka, 1) + 1;
                    @endphp

                    @foreach($terbuka_u->result() as $pt_u)
                    <option value="{{$pt_u->nomor_pertanyaan_terbuka}}"
                        <?php echo $ipg->is_next_step == $pt_u->nomor_pertanyaan_terbuka ? 'selected' : '' ?>>Lanjutkan
                        Ke {{$pt_u->nomor_pertanyaan_terbuka}}</option>
                    @endforeach

                    <option value="T{{$number_next}}"
                        <?php echo $ipg->is_next_step == 'T' . $number_next ? 'selected' : '' ?>>Lanjutkan Ke Pertanyaan
                        Unsur Berikutnya
                    </option>

                </select>
            </td>
            @endif

        </tr>
        @endforeach

        @else
        <tr>
            <td></td>
            <td>
                <textarea class="form-control" placeholder="Masukkan jawaban anda..."></textarea>
            </td>
        </tr>
        @endif
    </table>

</div>
@endforeach

<!----------------------------------------------------------------------------------------------------------------------------->
<!----------------------------------------------------- END TERBUKA ----------------------------------------------------------->
<!----------------------------------------------------------------------------------------------------------------------------->

@endforeach

<!----------------------------------------------------------------------------------------------------------------------------->
<!------------------------------------------------------- END UNSUR ----------------------------------------------------------->
<!----------------------------------------------------------------------------------------------------------------------------->





<!----------------------------------------------------------------------------------------------------------------------------->
<!----------------------------------------------------- START TERBUKA BAWAH --------------------------------------------------->
<!----------------------------------------------------------------------------------------------------------------------------->

@foreach($ci->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian
FROM pertanyaan_terbuka_$table_identity
JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id =
perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
WHERE is_letak_pertanyaan = 2")->result() as $pt_b)

@php
$model_tb = $pt_b->is_model_pilihan_ganda == 2 ? 'checkbox' : 'radio';
@endphp


<div class="card card-body shadow mb-5" style="border-left: 5px solid #ff0088;">
    <table class="table table-bordered" style="font-size: 14px;">
        <tr>
            <th width="4%">{{$pt_b->nomor_pertanyaan_terbuka}}.</th>
            <th colspan="2">{!! $pt_b->isi_pertanyaan_terbuka !!}</th>
        </tr>


        @if($pt_b->id_jenis_pilihan_jawaban == 1)
        @foreach($ci->db->query("SELECT * FROM isi_pertanyaan_ganda_$table_identity WHERE
        id_perincian_pertanyaan_terbuka = $pt_b->id_perincian")->result() as $ipg_b)
        <tr>
            <td></td>
            <td>
                <div class="{{$model_tb}}-inline" style="font-size: 14px;">
                    <label class="{{$model_tb}} {{$model_tb}}-outline {{$model_tb}}-success">
                        <input type="{{$model_tb}}" name="{{$pt_b->nomor_pertanyaan_terbuka}}"><span></span>
                        {{$ipg_b->pertanyaan_ganda}}
                    </label>
                </div>
            </td>

            @if($pt_b->is_model_pilihan_ganda == 1)
            <td width="35%">
                <select class="form-control form-control-sm" name="is_next_step[]" id="idt_{{$ipg_b->id}}">
                    @php
                    $terbuka_b = $ci->db->query("SELECT * FROM pertanyaan_terbuka_$table_identity
                    WHERE is_letak_pertanyaan = 2 && SUBSTR(nomor_pertanyaan_terbuka,2) >
                    SUBSTR('$pt_b->nomor_pertanyaan_terbuka', 2)");

                    $last_row_b = $terbuka_b->last_row();
                    $number_next = substr($last_row_b->nomor_pertanyaan_terbuka, 1) + 1;
                    @endphp

                    @foreach($terbuka_b->result() as $subpt_b)
                    <option value="{{$subpt_b->nomor_pertanyaan_terbuka}}"
                        <?php echo $ipg_b->is_next_step == $subpt_b->nomor_pertanyaan_terbuka ? 'selected' : '' ?>>
                        Lanjutkan
                        Ke {{$subpt_b->nomor_pertanyaan_terbuka}}</option>
                    @endforeach

                    <option value="T{{$number_next}}"
                        <?php echo $ipg_b->is_next_step == 'T' . $number_next ? 'selected' : '' ?>>Lanjutkan Ke
                        Pertanyaan Unsur Berikutnya
                    </option>

                </select>
            </td>
            @endif

        </tr>
        @endforeach

        @else
        <tr>
            <td></td>
            <td>
                <textarea class="form-control" placeholder="Masukkan jawaban anda..."></textarea>
            </td>
        </tr>
        @endif
    </table>

</div>
@endforeach

<!----------------------------------------------------------------------------------------------------------------------------->
<!----------------------------------------------------- END TERBUKA BAWAH ----------------------------------------------------->
<!----------------------------------------------------------------------------------------------------------------------------->

@endsection


@section('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>


@foreach($ci->db->get("kategori_unsur_pelayanan_$table_identity")->result() as $kup)
<script>
$(function() {
    $("#idu_{{$kup->id}}").change(function() {
        var is_next_step = $("#idu_{{$kup->id}}").val();
        $.ajax({
            url: "{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/update-alur-unsur/' . $kup->id . '/'}}" +
                is_next_step,
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            error: function(e) {
                Swal.fire(
                    'Error !',
                    e,
                    'error'
                )
            },
            success: function(data) {
                if (data.sukses) {
                    toastr["success"]('Berhasil diubah');
                }
            }
        })
        return false;
    });
});
</script>
@endforeach


@foreach($ci->db->get("isi_pertanyaan_ganda_$table_identity")->result() as $ipg_js)
<script>
$(function() {
    $("#idt_{{$ipg_js->id}}").change(function() {
        var is_next_step = $("#idt_{{$ipg_js->id}}").val();
        $.ajax({
            url: "{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/update-alur-terbuka/' . $ipg_js->id . '/'}}" +
                is_next_step,
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            error: function(e) {
                Swal.fire(
                    'Error !',
                    e,
                    'error'
                )
            },
            success: function(data) {
                if (data.sukses) {
                    toastr["success"]('Berhasil diubah');
                }
            }
        })
        return false;
    });
});
</script>
@endforeach


<!-- <script>
function tes() {
    $('#tes').html('COBACOBA');
};
</script> -->


@endsection
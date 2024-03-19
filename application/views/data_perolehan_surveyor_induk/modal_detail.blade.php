@php
$ci = get_instance();
@endphp


<div class="modal-header">
    <h5 class="modal-title text-primary" id="exampleModalLabel">{{$manage_survey->survey_name}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<div class="modal-body">


            @php
            $no = 1;
            $data_profil = [];
            $nama_profil_responden = [];
            foreach ($profil_responden as $get) {
                if ($get->jenis_isian == 1) {

                    $data_profil[] = "(SELECT nama_kategori_profil_responden FROM kategori_profil_responden_$table_identity WHERE responden_$table_identity.$get->nama_alias = kategori_profil_responden_$table_identity.id) AS $get->nama_alias";
                } else {
                    $data_profil[] = $get->nama_alias;
                }

                $profil = $get->nama_alias;
                $nama_profil_responden[] = '<th>' . $get->nama_profil_responden . '</th>';
            }
            $query_profil = implode(",", $data_profil);
            @endphp


<div class="table-responsive">
    <table class="table table-bordered mt-5 table-modal">
        <thead class="bg-secondary">
            <tr>
                <th width="5%">No</th>
                <th>Status</th>
                <th width="5%">Form</th>
                <th>Jenis Pelayanan</th>

                {!! implode("", $nama_profil_responden) !!}

                <th>Waktu Isi</th>
            </tr>
        </thead>
        <tbody>

            
            
            @foreach($ci->db->query("SELECT *, responden_$table_identity.uuid AS uuid_responden,
            (SELECT nama_layanan FROM layanan_survei_$table_identity WHERE id_layanan_survei = layanan_survei_$table_identity.id) AS nama_layanan,
            $query_profil
            FROM responden_$table_identity
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
            WHERE id_surveyor_induk != 0")->result() as $row)

            <tr>
                <td>{{$no++}}</td>
                <td>
                    @php
                    if ($row->is_submit == 1) {
                        $status = '<span class="badge badge-primary">Lengkap</span>';
                    } else {
                        $status = '<span class="badge badge-danger">Tidak Lengkap</span><br>
                        <small class="text-dark-50 font-italic">' . $row->is_end . '</small>';
                    }
                    @endphp

                    {!! $status !!}
                </td>

                <td>{!! anchor($manage_survey->slug . '/hasil-survei/' . $row->uuid_responden, '<i class="fas fa-file-pdf text-danger"></i>', ['target' => '_blank']) !!}</td>

                <td>{{$row->nama_layanan}}</td>
                
                @php
                foreach ($profil_responden as $get) {
				$profil = $get->nama_alias;
                    echo '<td>' . $row->$profil . '</td>';
                }
                @endphp

                <td>{{date("H:i d-m-Y", strtotime($row->waktu_isi))}}</td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>

<script>
    $(document).ready(function() {
        $(".table-modal").DataTable({
            "lengthMenu": [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "Semua data"]
            ],
            "pageLength": 5,
        });
    });
</script>

@php
$ci = get_instance();
@endphp


<h5 class="text-primary">{{$unsur->nomor_unsur . '. ' . $unsur->nama_unsur_pelayanan}}</h5>
<span>{!! $unsur->isi_pertanyaan_unsur !!}</span>
<hr>

<div class="table-responsive">
    <table class="table table-bordered table-hover example">
        <thead class="bg-secondary">
            <tr>
                <th width="5%">No</th>
                <th>Nama Responden</th>
                <th>Jawaban</th>
            </tr>
        </thead>
        <tbody>
            @php
            $no = 1;
            @endphp
            @foreach($responden->result() as $row)
            <tr>
                <td>{{$no++}}</td>
                <td>{{$row->nama_lengkap}}</td>
                <td>{{$row->skor_jawaban . ' (' . $row->nama_kategori_unsur_pelayanan . ')'}}
                    @if($row->skor_jawaban == 1 || $row->skor_jawaban == 2)
                    <br>
                    Alasan : {{$row->alasan_pilih_jawaban}}
                    @endif
                </td>
            </tr>
            @endforeach

        </tbody>


    </table>
</div>


<script>
    $(document).ready(function() {
        $('.example').DataTable();
    });
</script>
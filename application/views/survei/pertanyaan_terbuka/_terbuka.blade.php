@php
$n = $pertanyaan_terbuka_atas->num_rows() + 1;
@endphp

@foreach ($pertanyaan_terbuka->result() as $row_terbuka)
@if ($row_terbuka->id_unsur_pelayanan == $row->id_unsur_pelayanan)
@php
$is_required_t = $row_terbuka->is_required == 1 ? '<b class="text-danger">*</b>' : '';
@endphp

<div class=" mt-10 mb-10" id="display_{{$row_terbuka->nomor_pertanyaan_terbuka}}">
    <input type="hidden" name="id_pertanyaan_terbuka[{{ $row_terbuka->id_pertanyaan_terbuka }}]"
        value="{{$row_terbuka->id_pertanyaan_terbuka}}">
    <table class="table table-borderless" width="100%" border="0">
        <tr>
            <td width="5%" valign="top">{!! $row_terbuka->nomor_pertanyaan_terbuka . '' .
                $is_required_t !!}.</td>
            <td width="95%">{!! $row_terbuka->isi_pertanyaan_terbuka !!}</td>
        </tr>

        <tr>
            <td width="5%"></td>
            <td style="font-weight:bold;" width="95%">
                @foreach ($jawaban_pertanyaan_terbuka->result() as $value_terbuka)
                @if ($value_terbuka->id_perincian_pertanyaan_terbuka ==
                $row_terbuka->id_perincian_pertanyaan_terbuka)

                <div class="radio-inline mb-2">
                    <label class="radio radio-outline radio-success radio-lg" style="font-size: 16px;">
                        <input type="radio" name="jawaban_pertanyaan_terbuka[{{ $row_terbuka->id_pertanyaan_terbuka }}]"
                            value="{{ $value_terbuka->pertanyaan_ganda; }}"
                            class="terbuka_{{ $row_terbuka->nomor_pertanyaan_terbuka }}"
                            <?= $value_terbuka->pertanyaan_ganda == $row_terbuka->jawaban ? 'checked' : '' ?>
                            <?= $row_terbuka->stts_required ?>>
                        <span></span> {{ $value_terbuka->pertanyaan_ganda}}
                    </label>
                </div>
                @endif
                @endforeach


                @if ($row_terbuka->dengan_isian_lainnya == 1)

                <input class="form-control" name="jawaban_lainnya[{{ $row_terbuka->id_pertanyaan_terbuka }}]"
                    value="{{$row_terbuka->jawaban_lainnya}}" pattern="^[a-zA-Z0-9.,\s]*$|^\w$"
                    placeholder="Masukkan jawaban lainnya ..."
                    id="terbuka_lainnya_{{ $row_terbuka->nomor_pertanyaan_terbuka }}"
                    <?= $row_terbuka->jawaban == 'Lainnya' ? 'required' : 'style="display:none"' ?>>

                <small id="text_terbuka_{{ $row_terbuka->nomor_pertanyaan_terbuka }}" class="text-danger"
                    <?= $row_terbuka->jawaban == 'Lainnya' ? '' : 'style="display:none"' ?>>**Pengisian
                    form hanya dapat menggunakan tanda baca
                    (.) titik dan (,) koma</small>
                <br>
                @endif



                @if ($row_terbuka->id_jenis_pilihan_jawaban == 2)
                <textarea type="text"
                    name="jawaban_pertanyaan_terbuka[{{ $row_terbuka->id_pertanyaan_terbuka }}]"
                    class="form-control terbuka_{{ $row_terbuka->nomor_pertanyaan_terbuka }}"
                    placeholder="Masukkan Jawaban Anda ..."
                    <?= $row_terbuka->stts_required ?>>{{ $row_terbuka->jawaban }}</textarea>

                @endif
            </td>
        </tr>
    </table>
</div>
@endif

@php
$n++;
@endphp
@endforeach
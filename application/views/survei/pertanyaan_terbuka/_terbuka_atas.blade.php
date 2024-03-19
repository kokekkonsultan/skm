 <!-- Looping Pertanyaan Terbuka Paling Atas -->
 @php
 $a = 1;
 @endphp
 @foreach ($pertanyaan_terbuka_atas->result() as $row_terbuka_atas)
 @php
 $is_required_ta = $row_terbuka_atas->is_required == 1 ? '<b class="text-danger">*</b>' : '';
 @endphp

 <div class="mt-10 mb-10" id="display_{{$row_terbuka_atas->nomor_pertanyaan_terbuka}}">
     <input type="hidden" name="id_pertanyaan_terbuka[{{ $row_terbuka_atas->id_pertanyaan_terbuka }}]"
         value="{{$row_terbuka_atas->id_pertanyaan_terbuka}}">

     <table class="table table-borderless" width="100%" border="0">
         <tr>
             <td width="5%" valign="top">
                 {!! $row_terbuka_atas->nomor_pertanyaan_terbuka . '' . $is_required_ta !!}.
             </td>
             <td>{!! $row_terbuka_atas->isi_pertanyaan_terbuka !!}</td>
         </tr>

         <tr>
             <td width="5%"></td>
             <td style="font-weight:bold;" width="95%">

                 @foreach ($jawaban_pertanyaan_terbuka->result() as $value_terbuka_atas)
                 @if ($value_terbuka_atas->id_perincian_pertanyaan_terbuka ==
                 $row_terbuka_atas->id_perincian_pertanyaan_terbuka)
                 <div class="radio-inline mb-2">
                     <label class="radio radio-outline radio-success radio-lg" style="font-size: 16px;">
                         <input type="radio"
                             name="jawaban_pertanyaan_terbuka[{{ $row_terbuka_atas->id_pertanyaan_terbuka }}]"
                             value="{{ $value_terbuka_atas->pertanyaan_ganda; }}"
                             class="terbuka_{{ $row_terbuka_atas->nomor_pertanyaan_terbuka }}"
                             <?= $value_terbuka_atas->pertanyaan_ganda == $row_terbuka_atas->jawaban ? 'checked' : '' ?>
                             <?= $row_terbuka_atas->stts_required ?>>
                         <span></span> {{ $value_terbuka_atas->pertanyaan_ganda}}
                     </label>
                 </div>
                 @endif
                 @endforeach



                 @if ($row_terbuka_atas->dengan_isian_lainnya == 1)
                 <input class="form-control" name="jawaban_lainnya[{{ $row_terbuka_atas->id_pertanyaan_terbuka }}]"
                     value="{{$row_terbuka_atas->jawaban_lainnya}}" pattern="^[a-zA-Z0-9.,\s]*$|^\w$"
                     placeholder="Masukkan jawaban lainnya ..."
                     id="terbuka_lainnya_{{ $row_terbuka_atas->nomor_pertanyaan_terbuka }}"
                     <?= $row_terbuka_atas->jawaban == 'Lainnya' ? 'required' : 'style="display:none"' ?>>

                 <small id="text_terbuka_{{ $row_terbuka_atas->nomor_pertanyaan_terbuka }}" class="text-danger"
                     <?= $row_terbuka_atas->jawaban == 'Lainnya' ? '' : 'style="display:none"' ?>>**Pengisian form hanya
                     dapat menggunakan tanda baca
                     (.) titik dan (,) koma</small>
                 <br>
                 @endif


                 @if ($row_terbuka_atas->id_jenis_pilihan_jawaban == 2)
                 <textarea type="text"
                     name="jawaban_pertanyaan_terbuka[{{ $row_terbuka_atas->id_pertanyaan_terbuka }}]"
                     class="form-control terbuka_{{ $row_terbuka_atas->nomor_pertanyaan_terbuka }}"
                     placeholder="Masukkan Jawaban Anda ..."
                     <?= $row_terbuka_atas->stts_required ?>>{{ $row_terbuka_atas->jawaban }}</textarea>
                 @endif
             </td>
         </tr>
     </table>
 </div>
 @php
 $a++;
 @endphp
 @endforeach
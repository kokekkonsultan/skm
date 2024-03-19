<br>
<hr>
<div class="table-responsive mt-10">
    <table width="100%" class="table table-bordered" id='myolahdata' style="font-size: 12px;">
        <tr align="center">
            <th></th>
            <?php foreach ($unsur->result() as $row) { ?>
            <th class="bg-warning text-white"><?php echo $row->nomor_unsur ?></th>
            <?php } ?>
        </tr>
        <tr>
            <td class="bg-secondary"><strong>TOTAL</strong></td>
            @foreach ($koreksi_total->result() as $total)
            <td>
                <div align="center">
                    <strong><?php echo ROUND($total->sum_skor_jawaban, 3) ?></strong>
                </div>
            </td>
            @endforeach
        </tr>

        <tr>
            <td class="bg-secondary"><strong>Rata-Rata</strong></td>
            @foreach ($koreksi_rata_rata->result() as $rata_rata)
            <td>
                <div align="center"><?php echo ROUND($rata_rata->rata_rata, 3) ?></div>
            </td>
            @endforeach
        </tr>

        <tr>
            <td class="bg-secondary"><strong>Nilai per Unsur</strong></td>
            @foreach ($koreksi_nilai_per_unsur->result() as $nilai_per_unsur)
            <td colspan="<?php echo $nilai_per_unsur->colspan ?>">
                <div align="center">
                    <strong><?php echo ROUND($nilai_per_unsur->nilai_per_unsur, 3) ?></strong>
                </div>
            </td>
            @endforeach
        </tr>

        <tr>
            <td class="bg-secondary"><strong>Rata-Rata * Bobot</strong></td>
            <?php
            foreach ($koreksi_rata_rata_bobot->result() as $rata_rata_bobot) {
                $koreksi_nilai_bobot[] = $rata_rata_bobot->rata_rata_bobot;
                $koreksi_nilai_tertimbang = array_sum($koreksi_nilai_bobot);
                $koreksi_ikm = ROUND($koreksi_nilai_tertimbang * $skala_likert, 10);
            ?>
            <td colspan="<?php echo $rata_rata_bobot->colspan ?>">
                <div align="center">
                    <?php echo ROUND($rata_rata_bobot->rata_rata_bobot, 3) ?></div>
            </td>
            <?php
            } ?>
        </tr>

        <tr>
            <td class="bg-secondary"><strong>Nilai Rata2 Tertimbang</strong></td>
            <td colspan="{{$jumlah_pertanyaan}}">
                <div><?php echo ROUND($koreksi_nilai_tertimbang, 3) ?></div>
            </td>
        </tr>
        <tr>
            <td class="bg-secondary"><strong>IKM</strong></td>
            <td colspan="{{$jumlah_pertanyaan}}">
                <div> <strong><?php echo ROUND($koreksi_ikm, 3) ?></strong></div>
            </td>
        </tr>

        <?php
        foreach ($definisi_skala->result() as $obj) {
            if ($koreksi_ikm <= $obj->range_bawah && $koreksi_ikm >= $obj->range_atas) {
                $koreksi_kategori = $obj->kategori;
                $koreksi_mutu = $obj->mutu;
            }
        }
        if ($koreksi_ikm <= 0) {
            $kategori = 'NULL';
            $mutu = 'NULL';
        }

        // if ($koreksi_ikm <= 100 && $koreksi_ikm >= 88.31) {
        //     $koreksi_kategori = 'Sangat Baik';
        //     $koreksi_mutu = 'A';
        // } elseif ($koreksi_ikm <= 88.40 && $koreksi_ikm >= 76.61) {
        //     $koreksi_kategori = 'Baik';
        //     $koreksi_mutu = 'B';
        // } elseif ($koreksi_ikm <= 76.60 && $koreksi_ikm >= 65) {
        //     $koreksi_kategori = 'Kurang Baik';
        //     $koreksi_mutu = 'C';
        // } elseif ($koreksi_ikm <= 64.99 && $koreksi_ikm >= 25) {
        //     $koreksi_kategori = 'Tidak Baik';
        //     $koreksi_mutu = 'D';
        // } else {
        //     $koreksi_kategori = 'NULL';
        //     $koreksi_mutu = 'NULL';
        // }
        ?>

        <tr>
            <td class="bg-secondary"><strong>MUTU PELAYANAN</strong></td>
            <td colspan="{{$jumlah_pertanyaan}}">
                <div><strong>{{$koreksi_mutu}}</strong></div>
            </td>
        </tr>

        <tr>
            <td class="bg-secondary"><strong>KATEGORI</strong></td>
            <td colspan="{{$jumlah_pertanyaan}}">
                <div><strong>{{$koreksi_kategori}}</strong></div>
            </td>
        </tr>
    </table>
</div>
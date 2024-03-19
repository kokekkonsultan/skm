
<table style="width: 100%;">
    <tr>
        <td width="3%"><b><?= $no_Bab1++ ?>.</b></td>
        <td><b>Latar Belakang</b></td>
    </tr>
    <tr>
        <td></td>
        <td class="content-paragraph">
            Seiring dengan kemajuan teknologi dan tuntutan masyarakat dalam hal pelayanan, maka unit
            penyelenggara pelayanan publik dituntut untuk memenuhi harapan masyarakat dalam melakukan
            pelayanan.
        </td>
    </tr>
    <tr>
        <td></td>
        <td class="content-paragraph">
            Pelayanan publik yang dilakukan oleh aparatur pemerintah saat ini dirasakan belum memenuhi
            harapan masyarakat. Hal ini dapat diketahui dari berbagai keluhan masyarakat yang disampaikan
            melalui media massa dan jejaring sosial. Tentunya keluhan tersebut jika tidak ditangani akan
            memberikan dampak buruk terhadap pemerintah. Lebih jauh lagi adalah dapat menimbulkan
            ketidakpercayaan dari masyarakat.
        </td>
    </tr>
    <tr>
        <td></td>
        <td class="content-paragraph">
            Salah satu upaya yang harus dilakukan dalam perbaikan pelayanan publik adalah melakukan survei
            kepuasan masyarakat kepada pengguna layanan dengan mengukur kepuasan masyarakat pengguna
            layanan.
            <br>
            <br>
        </td>
    </tr>


    <tr>
        <td><b><?= $no_Bab1++ ?>.</b></td>
        <td><b>Tujuan</b></td>
    </tr>
    <tr>
        <td></td>
        <td class="content-paragraph">
            Kegiatan Survei Kepuasan Masyarakat terhadap pelayanan publik
            bertujuan untuk mendapatkan feedback/umpan balik atas kinerja pelayanan yang diberikan kepada
            masyarakat guna perbaikan dan peningkatan kinerja pelayanan secara berkesinambungan.
            <br>
            <br>
        </td>
    </tr>
    <tr>
        <td><b><?= $no_Bab1++ ?>.</b></td>
        <td><b>Metodologi</b></td>
    </tr>
</table>


<table style="width: 100%;">
    <tr>
        <td width="3%"></td>
        <td width="4%" valign="top" class="content-text">3.1</td>
        <td class="content-text">Populasi<br>Populasi dari kegiatan Survei Kepuasan Masyarakat adalah penyelenggara
            pelayanan publik, yaitu instansi pemerintah pusat dan pemerintah daerah, termasuk BUMN / BUMD dan BHMN
            menyesuaikan dengan lingkup yang akan disurvei.
        </td>
    </tr>

    <tr>
        <td></td>
        <td valign="top" class="content-text">3.2</td>
        <td class="content-text">Sampel<br>Sampel kegiatan Survei Kepuasan Masyarakat ditentukan dengan menggunakan
            perhitungan Krejcie and Morgan sebagai berikut:
            <br />
            <br />
            <b>Rumus Krejcie</b>
            <div style="text-align:center;">
                <img src="<?= base_url() . 'assets/img/site/rumus_krejcie.png' ?>" alt="rumus krejcie" width="50%">
            </div>
        </td>
    </tr>

    <tr>
        <td></td>
        <td></td>
        <td class="content-text">Keterangan :
            <div style="padding-left:4em;">
                <table style="width: 100%;">
                    <tr>
                        <td width="7%">&nbsp;S</td>
                        <td width="5%">:</td>
                        <td>Jumlah sampel</td>
                    </tr>
                    <tr>
                        <td width="7%"><img src="<?= base_url() . 'assets/img/site/lamda.png' ?>" alt="rumus krejcie"
                                width="60%"></td>
                        <td width="5%">:</td>
                        <td>Lamda (faktor pengali) dengan dk = 1,<br>
                            (taraf kesalahan yang digunakan 5%, sehingga nilai lamba
                            3,841)
                        </td>
                    </tr>
                    <tr>
                        <td width="7%">&nbsp;N</td>
                        <td width="5%">:</td>
                        <td>Populasi sebanyak
                            <?= $manage_survey->jumlah_populasi ?></td>
                    </tr>
                    <tr>
                        <td width="7%">&nbsp;P</td>
                        <td width="5%">:</td>
                        <td>Q = 0,5 (populasi menyebar normal)</td>
                    </tr>
                    <tr>
                        <td width="7%">&nbsp;d</td>
                        <td width="5%">:</td>
                        <td>0,05</td>
                    </tr>
                </table>
            </div>
            <div>Sehingga dari perhitungan di atas, jumlah responden minimal yang harus
                diperoleh adalah <?= $manage_survey->jumlah_sampling ?> responden.</div><br />
        </td>
    </tr>
</table>


<table style="width: 100%;">
    <tr>
        <td width="3%"><b><?= $no_Bab1++ ?>.</b></td>
        <td><b>Tim Survei Kepuasan Masyarakat</b></td>
    </tr>
    <tr>
        <td></td>
        <td class="content-text">
            Survei Kepuasan Masyarakat ini dilakukan oleh Tim Survei Kepuasan Masyarakat yang telah ditetapkan.
            <br>
            <br>
        </td>
    </tr>

    <tr>
        <td width="3%"><b><?= $no_Bab1++ ?>.</b></td>
        <td><b>Jadwal Survei Kepuasan Masyarakat</b></td>
    </tr>
    <tr>
        <td></td>
        <td class="content-text">
            Jadwal Survei Kepuasan Masyarakat dilakukan sesuai dengan jadwal yang telah ditentukan.
        </td>
    </tr>
</table>
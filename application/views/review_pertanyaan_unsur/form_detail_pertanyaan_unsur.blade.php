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

            <table class="table table-bordered">
                <?php
                $i = 1;
                foreach ($pertanyaan_unsur->result() as $row) {
                ?>
                <tr>
                    <td><b><?php echo $row->nomor; ?></b></td>
                    <td><b><?php echo $row->nama_unsur_pelayanan  ?></b>
                        <div class="mt-3" style="font-size: 1.1em">
                            <?php echo $row->isi_pertanyaan_unsur  ?>
                        </div>
                    </td>
                    <td>
                        <?php
                            $i = 1;
                            foreach ($jawaban_pertanyaan_unsur->result() as $value) {
                            ?>

                        <?php if ($row->id_pertanyaan_unsur == $value->id_pertanyaan_unsur) : ?>
                        <label>
                            <input type="radio"> <?php echo $value->nama_kategori_unsur_pelayanan  ?>
                        </label>

                        <?php endif; ?>

                        <?php
                            }
                            ?>
                    </td>
                </tr>

                <?php
                    $i = 1;
                    foreach ($pertanyaan_terbuka->result() as $pt) {
                    ?>

                <?php if ($row->id_unsur_pelayanan == $pt->id_unsur_pelayanan) : ?>
                <tr>
                    <td><b><?php echo $pt->nomor_pertanyaan_terbuka; ?></b></td>
                    <td><b><?php echo $pt->nama_pertanyaan_terbuka  ?></b>
                        <div class="mt-3" style="font-size: 1.1em">
                            <?php echo $pt->isi_pertanyaan_terbuka  ?>
                        </div>
                    </td>

                    <td>
                        <?php
                                    $i = 1;
                                    foreach ($jawaban_pertanyaan_terbuka->result() as $value) {
                                    ?>

                        <?php if ($pt->id_perincian_pertanyaan_terbuka == $value->id_perincian_pertanyaan_terbuka) : ?>
                        <label>
                            <input type="radio"> <?php echo $value->pertanyaan_ganda  ?>
                        </label>

                        <?php endif; ?>

                        <?php
                                    }
                                    ?>

                        <?php if ($pt->lainnya != NULL) : ?>
                        <label>
                            <input type="radio"> <?php echo $pt->lainnya  ?>
                        </label>
                        <?php endif; ?>
                    </td>

                </tr>
                <?php endif; ?>

                <?php
                    }
                    ?>


                <?php
                }
                ?>
            </table>

        </div>
    </div>
</div>

@endsection

@section('javascript')

@endsection
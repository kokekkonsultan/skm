@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">


            @include('data_survey_klien/menu_data_survey_klien')

        </div>
        <div class="col-md-9">
            <div class="card" data-aos="fade-down" data-aos-delay="300">
                <div class="card-header">
                    {{ $title }}
                </div>
                <div class="card-body">
                    <div class="mt-5">
                        <table class="table table-hover mt-5">
                            <tr>
                                <th>No</th>
                                <th>Surveyor</th>
                                <th>Total Perolehan</th>

                            </tr>

                            <?php
                            $no = 1;
                            foreach ($get_data->result() as $row) {
                            ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><strong><?php echo $row->kode_surveyor ?></strong> -- <?php echo $row->first_name ?>
                                        <?php echo $row->last_name ?></td>

                                    <td><a class="btn btn-light-danger"><?php echo $row->total_survey ?></a></td>

                                    <!-- <td>
                                    <?php
                                    echo anchor(base_url() . 'data-survey-klien/perolehan-surveyor/' . $row->table_identity . '/' . $row->uuid, 'Detail <i class="fas fa-arrow-right"></i>', ['class' => 'btn btn-light-primary font-weight-bold']);
                                    ?>
                                </td> -->

                                </tr>
                            <?php
                            } ?>
                        </table>
                    </div>


                </div>
            </div>

        </div>
    </div>

</div>

@endsection

@section('javascript')

@endsection
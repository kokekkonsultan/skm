@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container-fluid">

    <div class="row mt-5">
        <div class="col-md-12">

            <div class="card" data-aos="fade-down">
                <div class="card-header bg-secondary">
                    <h5>{{ $title }}</h5>
                </div>
                <div class="card-body">
                    <span class="text-danger"><?php echo validation_errors(); ?></span>
                    <br>

                    <form action="{{base_url() .$ci->uri->segment(1). '/kelompok-akun/add'}}" method="POST">

                    <div class="row">
                        <label class="col-sm-3 col-form-label
                        font-weight-bold">Nama Kelompok <span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="nama_kelompok" value="" class="form-control" required autofocus>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <label class="col-sm-3 col-form-label
                        font-weight-bold">Obyek Kelompok <span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <select id="id_survei" name="id_survei[]" class="form-control" multiple="multiple" required>

                                @php
                                $user_id = $ci->session->userdata('user_id');
                                @endphp
                                @foreach($ci->db->get_where('users', ['id_parent_induk' => $user_id])->result() as $row)
                                <optgroup label="{{$row->first_name . ' ' . $row->last_name}}">
                                    @foreach($ci->db->get_where('manage_survey', ['id_user' => $row->id])->result() as $value)
                                        <option value="{{$value->id}}">{{$value->survey_name}}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                                </select>
                        </div>
                    </div>
                    
                   
                    </br>
                    </br>


                    <div class="text-right">
                        <a class="btn btn-light-primary font-weight-bold" href="{{base_url() .$ci->uri->segment(1) .'/kelompok-akun'}}">Batal</a>
                        <button class="btn btn-primary font-weight-bold" type="submit">Simpan</button>
                    </div>


                    </form>
                </div>
            </div>

        </div>
    </div>

</div>

@endsection

@section ('javascript')
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


<script>
$(document).ready(function() {

    $("#id_survei").select2({
        placeholder: "   Please Select",
        allowClear: true
    });

});
</script>
@endsection
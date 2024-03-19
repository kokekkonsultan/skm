@extends('include_backend/template_no_aside')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container-fluid">

    <div class="row justify-content-md-center">
        <div class="col col-lg-4">
            <div class="card mt-5">
                <div class="card-header bg-secondary font-weight-bold">
                    {{ $title }}
                </div>
                <div class="card-body">

                    <h5>Isikan nilai hanya pada bidang berwarna kuning !</h5>
                    <br>

                    <form id="formJs" name="formJs" action="" method="post" enctype="multipart/form-data">
                        <label class="font-weight-bold">Z:</label>
                        <input type="text" name="val_z" onkeyup="OnChange(this.value)"
                            onKeyPress="return isNumberKey(event)" value="1.96" class="form-control">

                        <label class="font-weight-bold">p:</label>
                        <input type="text" name="val_p" onkeyup="OnChange(this.value)"
                            onKeyPress="return isNumberKey(event)" value="0.5" class="form-control">

                        <label class="font-weight-bold">q:</label>
                        <input type="text" name="val_q" onkeyup="OnChange(this.value)"
                            onKeyPress="return isNumberKey(event)" value="0.5" class="form-control">

                        <label class="font-weight-bold">d:</label>
                        <input type="text" name="val_d" onkeyup="OnChange(this.value)"
                            onKeyPress="return isNumberKey(event)" class="form-control"
                            style="background-color: yellow;">

                        <label class="font-weight-bold">n:</label>
                        <input type="text" name="txtDisplay" value="" class="form-control" readonly="readonly"
                            style="background-color: black; color: #FFFFFF;">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')

<script>
// dim_lambda = document.formJs.lambda.value;
// document.formJs.txtDisplay.value = dim_lambda;

dim_d = document.formJs.val_d.value;
document.formJs.txtDisplay.value = dim_d;

// dim_populasi_menyebar = document.formJs.populasi_menyebar.value;
// document.formJs.txtDisplay.value = dim_populasi_menyebar;

// dim_val_d = document.formJs.val_d.value;
// document.formJs.txtDisplay.value = dim_val_d;

function OnChange(value) {
    dim_val_z = document.formJs.val_z.value;
    dim_val_p = document.formJs.val_p.value;
    dim_val_q = document.formJs.val_q.value;
    dim_val_d = document.formJs.val_d.value;

    total = (((dim_val_z * dim_val_z) * dim_val_p * dim_val_q) / (dim_val_d * dim_val_d));

    document.formJs.txtDisplay.value = Math.ceil(total);
}
</script>

@endsection
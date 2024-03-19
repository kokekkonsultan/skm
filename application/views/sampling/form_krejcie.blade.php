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
                        <label class="font-weight-bold">lambda:</label>
                        <input type="text" name="lambda" onkeyup="OnChange(this.value)"
                            onKeyPress="return isNumberKey(event)" value="3.841" class="form-control"><br>

                        <label class="font-weight-bold">N:</label>
                        <input type="text" name="populasi" onkeyup="OnChange(this.value)"
                            onKeyPress="return isNumberKey(event)" class="form-control"
                            style="background-color: yellow;"><br>

                        <label class="font-weight-bold">P=Q:</label>
                        <input type="text" name="populasi_menyebar" onkeyup="OnChange(this.value)"
                            onKeyPress="return isNumberKey(event)" value="0.5" class="form-control"><br>

                        <label class="font-weight-bold">d:</label>
                        <input type="text" name="val_d" onkeyup="OnChange(this.value)"
                            onKeyPress="return isNumberKey(event)" value="0.05" class="form-control"><br>

                        <label class="font-weight-bold">S:</label>
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
dim_lambda = document.formJs.lambda.value;
document.formJs.txtDisplay.value = dim_lambda;

dim_populasi = document.formJs.populasi.value;
document.formJs.txtDisplay.value = dim_populasi;

// dim_populasi_menyebar = document.formJs.populasi_menyebar.value;
// document.formJs.txtDisplay.value = dim_populasi_menyebar;

// dim_val_d = document.formJs.val_d.value;
// document.formJs.txtDisplay.value = dim_val_d;

function OnChange(value) {
    dim_lambda = document.formJs.lambda.value;
    dim_populasi = document.formJs.populasi.value;
    dim_populasi_menyebar = document.formJs.populasi_menyebar.value;
    dim_val_d = document.formJs.val_d.value;

    total = (dim_lambda * dim_populasi * dim_populasi_menyebar * dim_populasi_menyebar) / ((dim_val_d * dim_val_d) * (
        dim_populasi - 1) + (dim_lambda * dim_populasi_menyebar * dim_populasi_menyebar));

    document.formJs.txtDisplay.value = Math.ceil(total);
}
</script>

@endsection
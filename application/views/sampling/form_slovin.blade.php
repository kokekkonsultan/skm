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
                        <label class="font-weight-bold">N:</label>
                        <input type="text" name="val_n" onkeyup="OnChange(this.value)"
                            onKeyPress="return isNumberKey(event)" class="form-control"
                            style="background-color: yellow;">

                        <label class="font-weight-bold">e:</label>
                        <input type="text" name="val_e" onkeyup="OnChange(this.value)"
                            onKeyPress="return isNumberKey(event)" value="0.05" class="form-control">

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
dim_n = document.formJs.val_n.value;
document.formJs.txtDisplay.value = dim_n;

function OnChange(value) {
    dim_val_n = document.formJs.val_n.value;
    dim_val_e = document.formJs.val_e.value;

    total = dim_val_n / (1 + dim_val_n * (dim_val_e * dim_val_e));

    document.formJs.txtDisplay.value = Math.ceil(total);
}
</script>

@endsection
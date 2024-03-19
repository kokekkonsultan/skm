@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
    type="text/css" />
@endsection

@section('content')

<div class="container mt-5">

    <div class="card" data-aos="fade-up">
        <div class="card-header bg-secondary font-weight-bold">
            {{$title}}
        </div>
        <div class="card-body">

            <div class="text-right mb-3">
                <a class="btn btn-primary font-weight-bold shadow-lg"
                    href="{{base_url().'pengguna-klien-induk/create'}}"><i class="fas fa-plus"></i> Tambah Klien
                    Induk</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered example" style="width:100%">
                    <thead class="bg-secondary">
                        <tr>
                            <th>No.</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Groups</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $no = 1;
                        @endphp
                        @foreach($users->result() as $value)
                        <tr>
                            <td>{{$no++}}</td>
                            <td>{{$value->first_name}}</td>
                            <td>{{$value->last_name}}</td>
                            <td>{{$value->username}}</td>
                            <td>{{$value->email}}</td>
                            <td>{{$value->name_groups}}</td>
                            <td><a class="text-primary"
                                    href="{{base_url().'pengguna-klien-induk/edit/' . $value->user_id}}">Edit</a>
                            </td>
                            <td>
                                <a class="text-danger"
                                    href="{{base_url().'pengguna-klien-induk/delete/' . $value->user_id}}"
                                    onclick="return confirm('Apakah anda yakin ingin menghapus akun ini?')">Delete</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>


@endsection

@section('javascript')
<script src=" {{ base_url() }}assets/themes/metronic/assets/plugins/custom/datatables/datatables.bundle.js"> </script>
<script>
$(document).ready(function() {
    $('.example').DataTable();
});
</script>
@endsection
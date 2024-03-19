<div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Divisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="<?php echo base_url() . $ci->session->userdata('username') . '/add-division' ?>"
                    method="POST">

                    <input name="id_berlangganan" value="{{$data_langganan->id_berlangganan}}" hidden>
                    <input name="uuid_berlangganan" value="{{$ci->uri->segment(4)}}" hidden>

                    <div class="form-group">
                        <label class="form-label font-weight-bold">Nama Divisi <span style="color:red;">*</span></label>
                        <input class="form-control" name="division_name" autofocus required>
                    </div>

                    <div class="text-right">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>


@foreach($division->result() as $row)
<div class="modal fade" id="edit{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title" id="exampleModalLabel">Edit Divisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="<?php echo base_url() . $ci->session->userdata('username') . '/edit-division' ?>"
                    method="POST">

                    <input name="id" value="{{$row->id}}" hidden>
                    <input name="uuid_berlangganan" value="{{$ci->uri->segment(4)}}" hidden>

                    <div class="form-group">
                        <label class="form-label font-weight-bold">Nama Divisi <span style="color:red;">*</span></label>
                        <input class="form-control" name="division_name" value="{{$row->division_name}}" autofocus
                            required>
                    </div>

                    <div class="text-right">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@foreach($supervisor->result() as $value)
<div class="modal fade" id="detail{{$value->user_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title" id="exampleModalLabel">Detail Supervisor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <table id="table" class="table table-hover" cellspacing="0" width="100%">
                    <tr>
                        <th width="40%">Nama Lengkap</th>
                        <td><?php echo $value->first_name ?> <?php echo $value->last_name ?></td>
                    </tr>
                    <tr>
                        <th width="40%">Username</th>
                        <td class="text-primary"><?php echo $value->username ?></td>
                    </tr>
                    <tr>
                        <th width="40%">Email</th>
                        <td><?php echo $value->email ?></td>
                    </tr>
                    <tr>
                        <th width="40%">Telephone</th>
                        <td><?php echo $value->phone ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endforeach
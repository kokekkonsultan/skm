<!----------------- MODAL EMAIL ------------------------>
<?php
foreach ($surveyor->result() as $ps) {
?>
<div class="example-modal">
    <div id="detail<?php echo $ps->id_user ?>" class="modal fade" role="dialog" style="display:none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h5 class="font-weight-bold">Detail Surveyor -- <b><?php echo $ps->kode_surveyor ?></b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="" id="kt_blockui_content">
                        <table id="table" class="table table-hover" cellspacing="0" width="100%">
                            <tr>
                                <th width="40%">Nama Lengkap</th>
                                <td><?php echo $ps->first_name ?> <?php echo $ps->last_name ?></td>
                            </tr>
                            <tr>
                                <th width="40%">Kode Surveyor</th>
                                <td><?php echo $ps->kode_surveyor ?></td>
                            </tr>
                            <tr>
                                <th width="40%">Username</th>
                                <td class="text-primary"><?php echo $ps->username ?></td>
                            </tr>
                            <tr>
                                <th width="40%">Nama Perusahaan</th>
                                <td><?php echo $ps->company ?></td>
                            </tr>
                            <tr>
                                <th width="40%">Email</th>
                                <td><?php echo $ps->email ?></td>
                            </tr>
                            <tr>
                                <th width="40%">Telephone</th>
                                <td><?php echo $ps->phone ?></td>
                            </tr>
                            <tr>
                                <th width="40%">Link Survei</th>
                                <td>
                                    <div class='input-group'>
                                        <input class='form-control form-control-sm' id='kt_clipboard{{$ps->id_user}}'
                                            value="<?php echo base_url() . 'survei/' . $ci->uri->segment(2) . '/' . $ps->uuid_surveyor ?>"
                                            readonly>
                                        <div class='input-group-append'>
                                            <a href='javascript:void(0)' class='btn btn-light-primary btn-sm'
                                                data-clipboard='true'
                                                data-clipboard-target='#kt_clipboard{{$ps->id_user}}'><i
                                                    class='la la-copy'></i></a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}
?>
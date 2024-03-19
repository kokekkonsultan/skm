@php
$ci = get_instance();
@endphp

<div class="card card-custom card-sticky mt-5">
    <div class="card-header bg-light-primary">
        <div class="card-title">
            Data Berlangganan
        </div>
        <div class="card-toolbar">
        </div>
    </div>
    <div class="card-body">
        {!! $table !!}
    </div>
</div>


<div class="modal fade bd-example-modal-xl" id="modalDetail" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" id="bodyModalDetail">

            </div>
        </div>
    </div>
</div>

<script>
function showDetail(id) {
    $.ajax({
        type: "post",
        url: "{{ base_url() }}{{ $ci->session->userdata('username') }}/info-berlangganan/get-invoice",
        data: "id=" + id,
        dataType: "html",
        success: function(response) {
            $('#modalDetail').modal('show');
            $('.modal-title').text('INVOICE');

            $('#bodyModalDetail').empty();
            $('#bodyModalDetail').append(response);
        }
    });
}
</script>
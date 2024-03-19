@php 
    $ci = get_instance();
@endphp

@if ($data_survey->num_rows() > 0)
<h4>Survey Anda</h4>

<div class="row mt-3">
    @foreach ($data_survey->result() as $value)
    <div class="col-md-6 mt-3">

<div class="card card-custom mb-8 mb-lg-0">
 <div class="card-body">
  <div class="d-flex align-items-center p-5">
   <div class="mr-6">
    <span class="svg-icon svg-icon-4x">
     <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Clipboard-check.svg-->
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <rect x="0" y="0" width="24" height="24" />
                <path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" fill="#000000" opacity="0.3" />
                <path d="M10.875,15.75 C10.6354167,15.75 10.3958333,15.6541667 10.2041667,15.4625 L8.2875,13.5458333 C7.90416667,13.1625 7.90416667,12.5875 8.2875,12.2041667 C8.67083333,11.8208333 9.29375,11.8208333 9.62916667,12.2041667 L10.875,13.45 L14.0375,10.2875 C14.4208333,9.90416667 14.9958333,9.90416667 15.3791667,10.2875 C15.7625,10.6708333 15.7625,11.2458333 15.3791667,11.6291667 L11.5458333,15.4625 C11.3541667,15.6541667 11.1145833,15.75 10.875,15.75 Z" fill="#000000" />
                <path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" fill="#000000" />
            </g>
        </svg>
        <!--end::Svg Icon-->
    </span>
   </div>
   <div class="d-flex flex-column">
    <a href="javascript:void(0)" class="text-dark text-hover-primary font-weight-bold font-size-h4 mb-3" onclick="showDetailSurvey('{{ $value->slug }}')" title="Detail">
        {{ $value->survey_name }}
    </a>
    <div class="text-dark-75">
     {{ $value->description }}
    </div>
   </div>
  </div>
 </div>
</div>
        {{-- <div class="card" style="height: 100px;">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        @php
                        echo anchor(base_url().$ci->session->userdata('username').'/'.$value->slug, $value->slug, ['class' => 'fw-bold'])
                        @endphp
                        <br>
                        <small>{{ $value->description }}</small>

                    </div>
                    <div class="col-md-4 text-end">
                        @php
                        $is_privacy = ($value->is_privacy == 1) ? "Public" : "Private";
                        $color_pill = ($value->is_privacy == 1) ? "bg-dark" : "bg-secondary";
                        @endphp
                        <span class="badge {{ $color_pill }} text-white">{{ $is_privacy }}</span>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

    @endforeach
</div>
@else
<div class="fs-5 lead text-center mt-5 text-primary">
    Organisasi anda belum melakukan survey
</div>
@endif

<script>
function showDetailSurvey(id)
{
    $.ajax({
        type: "post",
        url: "{{ base_url() }}{{ $ci->uri->segment(1) }}/overview/detail-survey",
        data: "id="+id,
        dataType: "html",
        success: function (response) {
            $('#modalDetail').modal('show');
            $('.modal-title').text('Detail Survei Anda');

            $('#bodyModalDetail').empty();
            $('#bodyModalDetail').append(response);
        }
    });
}
</script>
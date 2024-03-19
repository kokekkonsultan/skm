@php 
    $ci = get_instance();
@endphp

<link rel="stylesheet" href="{{ base_url() }}assets/vendor/owlcarousel2/assets/owl.carousel.min.css">
<link rel="stylesheet" href="{{ base_url() }}assets/vendor/owlcarousel2/assets/owl.theme.default.min.css">

<h4 class="mt-5 mb-5">Campaign</h4>

<div class="mt-5 mb-5">

    <div class="owl-carousel owl-theme mb-5">
    @foreach ($paket->result() as $value)
        <div class="item">
            

            <div class="card card-custom wave wave-animate-slow mb-8 mb-lg-0">
                <div class="card-body">
                <div class="d-flex align-items-center p-5">
                <div class="mr-6">
                <span class="svg-icon svg-icon-warning svg-icon-4x">
                    <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24" />
                            <path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" fill="#000000" opacity="0.3" />
                            <path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" fill="#000000" />
                        </g>
                    </svg>
                    <!--end::Svg Icon-->
                </span>
                </div>
                <div class="d-flex flex-column">
                <a href="javascript:void(0)" class="text-dark text-hover-primary font-weight-bold font-size-h4 mb-3" onclick="showDetail('{{ $value->id }}')" title="Detail Campaign">
                {{ $value->nama_paket }}
                </a>
                <div class="text-dark-75">
                    {!! $value->deskripsi_paket !!}
                </div>
                </div>
                </div>
                </div>
            </div>



        </div>
    @endforeach
    </div>

</div>

<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
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


<script src="{{ base_url() }}assets/vendor/owlcarousel2/owl.carousel.min.js"></script>
<script>
$('.owl-carousel').owlCarousel({
    autoplay:true,
    loop:true,
    margin:10,
    // nav:true,
    navigation : false,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:3
        },
        1000:{
            items:3
        }
    }
});

function showDetail(id)
{
    $.ajax({
        type: "post",
        url: "{{ base_url() }}{{ $ci->uri->segment(1) }}/overview/detail-packet",
        data: "id="+id,
        dataType: "html",
        success: function (response) {
            $('#modalDetail').modal('show');
            $('.modal-title').text('(Campaign) Detail Paket');

            $('#bodyModalDetail').empty();
            $('#bodyModalDetail').append(response);
        }
    });
}
</script>
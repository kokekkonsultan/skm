@php
$ci = get_instance();
@endphp


        @php
        if ($profiles->is_privacy == 1) {
        $status = '<a
            href="'.base_url().$ci->session->userdata('username').'/'.$ci->uri->segment(2).'/do/change-privacy"
            title="Klik untuk mengubah privasi"><span class="badge badge-success">Public</span></a>';
        $color = 'primary'; 
        } else {
        $status = '<a
            href="'.base_url().$ci->session->userdata('username').'/'.$ci->uri->segment(2).'/do/change-privacy"
            title="Klik untuk mengubah privasi"><span class="badge badge-danger">Private</span></a>';
        $color = 'primary';
        };
        $icon = ($profiles->is_privacy == 1) ? '<i class="fas fa-lock-open"></i>' : '<i class="fas fa-lock"></i>';
        @endphp


<div class="card card-custom wave wave-animate-slow" data-aos="fade-down">
    <!--begin::Body-->
    <div class="card-body">

        <?php echo $icon . '  ' . anchor(base_url().$ci->uri->segment(1).'/manage-survey', $ci->uri->segment(1), ['class' =>
        "font-weight-bold text-$color"]) . '/' . anchor(base_url().$ci->uri->segment(1).'/'.$ci->uri->segment(2).'/do', $ci->uri->segment(2), ['class' =>
        "font-weight-bold text-$color"]) . ' '// . $status ?>

    </div>
    <!--end::Body-->
</div>





<!-- <div class="card" data-aos="fade-down">
    <div class="card-body">



        @php
        if ($profiles->is_privacy == 1) {
        $status = '<a
            href="'.base_url().$ci->session->userdata('username').'/'.$ci->uri->segment(2).'/do/change-privacy"
            title="Klik untuk mengubah privasi"><span class="badge badge-primary">Public</span></a>';
        } else {
        $status = '<a
            href="'.base_url().$ci->session->userdata('username').'/'.$ci->uri->segment(2).'/do/change-privacy"
            title="Klik untuk mengubah privasi"><span class="badge badge-warning">Private</span></a>';
        };
        $icon = ($profiles->is_privacy == 1) ? '<i class="fas fa-lock-open"></i>' : '<i class="fas fa-lock"></i>';
        @endphp

        @php
        echo $icon
        @endphp
        @php
        echo anchor(base_url().$ci->uri->segment(1).'/manage-survey', $ci->uri->segment(1), ['class' =>
        'font-weight-bold'])
        @endphp
        /
        @php
        echo anchor(base_url().$ci->uri->segment(1).'/'.$ci->uri->segment(2).'/do', $ci->uri->segment(2), ['class' =>
        'font-weight-bold'])
        @endphp

        {!! $status !!}

    </div>
</div> -->

<!-- <div class="card card-custom bgi-no-repeat gutter-b"
    style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(https://evaluasi-kelembagaan.com/assets/themes/metronic/assets/media/svg/patterns/taieri.svg )">
    <div class="card-body d-flex align-items-center">
        <div>
            <h3 class="text-white font-weight-bolder line-height-lg mb-5">HASIL EVALUASI
                <br>DIREKTORAT JENDERAL PERATURAN PERUNDANG-UNDANGAN TAHUN 2022
            </h3>

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                <i class="fas fa-print"></i> Cetak
            </button>
        </div>
    </div>
</div> -->
<div class="" data-aos="fade-down">
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: white;">
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-item nav-link
      <?php if ($ci->uri->segment(4) == 'survey') {
            echo 'active text-primary';
        } ?>
      " href="{{ base_url() }}{{ $ci->session->userdata('username') }}/{{ $ci->uri->segment(2) }}/settings/survey"><i
                        class="fa fa-clock"></i> Pengaturan Waktu Survei</span></a>

                <!-- <a class="nav-item nav-link 
      <?php if ($ci->uri->segment(4) == 'display') {
            echo 'active text-primary';
        } ?>" href="{{ base_url() }}{{ $ci->session->userdata('username') }}/{{ $ci->uri->segment(2) }}/settings/display"><i class="fa fa-tasks"></i> Pengaturan Form Survei</a> -->

                <a class="nav-item nav-link 
      <?php if ($ci->uri->segment(4) == '') {
            echo 'active text-primary';
        } ?>" href="{{ base_url() }}{{ $ci->session->userdata('username') }}/{{ $ci->uri->segment(2) }}/settings"><i
                        class="fas fa-cog"></i> Pengaturan Umum</a>
            </div>
        </div>
    </nav>
</div>
{{-- <section style="background-color: #3D4C6F">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <div class="bg-primary text-white p-5 p-lg-6 rounded-3">
          <h4 class="text-white fs-1 fs-lg-2 mb-1">Sign up for good news emails</h4>
          <p class="text-white">Stay current with our latest insights</p>
            {!! form_open(base_url().'subscribers', ['class' => 'mt-4']) !!}
            <div class="row align-items-center">
              <div class="col-md-7 pe-md-0">
                <div class="input-group">
                  <input class="form-control" name="your_mail" type="email" placeholder="Enter Email Here" /></div>
              </div>
              <div class="col-md-5 mt-3 mt-md-0">
                <div class="d-grid"><button class="btn btn-warning" type="submit"><span class="text-primary fw-semi-bold">Submit</span></button></div>
              </div>
            </div>
          {!! form_close() !!}
        </div>
      </div>
      <div class="col-lg-6 mt-4 mt-lg-0">
        <div class="row">
          <div class="col-6 col-lg-4 text-white ms-lg-auto">
            <ul class="list-unstyled">
              <li class="mb-3"><a class="text-white" href="{{ base_url() }}contact">Contact Us</a></li>
              <li class="mb-3"><a class="text-white" href="{{ base_url() }}event">Event</a></li>
              <li class="mb-3"><a class="text-white" href="{{ base_url() }}news">News</a></li>
              <li class="mb-3"><a class="text-white" href="{{ base_url() }}testimony">Testimonials</a></li>
              <li class="mb-3"><a class="text-white" href="{{ base_url() }}downloads">Downloads</a></li>
              <li class="mb-3"><a class="text-white" href="{{ base_url() }}career">Career</a></li>
            </ul>
          </div>
          <div class="col-6 col-sm-5 ms-sm-auto">
            <ul class="list-unstyled">
              <li class="mb-3"><a class="text-decoration-none d-flex align-items-center" href="https://id.linkedin.com/company/pt.-kokek"> <span class="brand-icon me-3"><span class="fab fa-linkedin-in"></span></span>
                  <h5 class="fs-0 text-white mb-0 d-inline-block">Linkedin</h5>
                </a></li>
              <li class="mb-3"><a class="text-decoration-none d-flex align-items-center" href="https://instagram.com/pt.kokek"> <span class="brand-icon me-3"><span class="fab fa-instagram"></span></span>
                  <h5 class="fs-0 text-white mb-0 d-inline-block">Instagram</h5>
                </a></li>
              <li class="mb-3"><a class="text-decoration-none d-flex align-items-center" href="https://www.facebook.com/pt.kokek/"> <span class="brand-icon me-3"><span class="fab fa-facebook-f"></span></span>
                  <h5 class="fs-0 text-white mb-0 d-inline-block">Facebook</h5>
                </a></li>
              <li class="mb-3"><a class="text-decoration-none d-flex align-items-center" href="https://api.whatsapp.com/send?phone=6289526814555"> <span class="brand-icon me-3"><span class="fab fa-whatsapp"></span></span>
                  <h5 class="fs-0 text-white mb-0 d-inline-block">Whatsapp</h5>
                </a></li>
            </ul>
            <div>
              <img src="{{ base_url() }}assets/img/kokek-bsi.png" alt="" style="width: 300px;">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section> --}}

<footer class="footer bg-primary text-center py-4">
  <div class="container">
    <div class="row align-items-center opacity-85 text-white">
      <div class="col-sm-3 text-sm-start">{{-- <a href="index-2.html"><img src="{{ base_url() }}assets/themes/elixir/v3.0.0/assets/img/logo-light.png" alt="logo" /></a> --}}</div>
      <div class="col-sm-6 mt-3 mt-sm-0">
        <p class="lh-lg mb-0 fw-semi-bold">&copy; Copyright {{ date("Y") }}</p>
      </div>
      <div class="col text-sm-end mt-3 mt-sm-0">{{-- <span class="fw-semi-bold">Programmed by </span><a class="text-white" href="{{ base_url() }}" target="_blank">IT</a> --}}</div>
    </div>
  </div>
</footer>

<footer class="footer">
    <div class="menu-bg">
      <div class="container-xl row pt-4 footer-link">
        <div class="col-sm-12 col-md-5 logo">
          <img loading="lazy" class="footer-logo" src="{{ asset('uploads/logo/logo.png') }}" />
          <p class="mt-2">
            {{ textLogo() }}
          </p>
        </div>
        <div class="col-sm-12 col-md-3 useful-links">
          <h4 class="text-white mb-4">Useful Links</h4>
          <ul class="list-unstyled">
            <li>
                <a href="{{ route('home') }}" class="{{ Route::is('home') ? 'active' : '' }}">
                    <i class="bi bi-caret-right pe-3"></i>Home
                </a>
            </li>
            <li>
                <a href="{{ route('about') }}" class="{{ Route::is('about') ? 'active' : '' }}">
                    <i class="bi bi-caret-right pe-3"></i>About
                </a>
            </li>
            <li><a href="{{ route('courses.index') }}" class="{{ Route::is('courses.*') ? 'active' : '' }}"><i class="bi bi-caret-right pe-3"></i>Course</a>
            </li>
            <li><a href="{{ route('contact')}}" class="{{ Route::is('contact') ? 'active' : '' }}"><i class="bi bi-caret-right pe-3"></i>Contact</a>
            </li>
            <li><a href="{{ route('privacy.policy')}}" class="{{ Route::is('privacy.policy') ? 'active' : '' }}"><i class="bi bi-caret-right pe-3"></i>Privacy Policy</a>
            </li>
            <li><a href="{{ route('terms.condition')}}" class="{{ Route::is('terms.condition') ? 'active' : '' }}"><i class="bi bi-caret-right pe-3"></i>Terms & Condition</a>
            </li>
          </ul>
        </div>
        <div class="col-sm-12 col-md-4 contact-info">
          <h4 class="text-white mb-4">Contact info</h4>
          @php
            $contact = contact();
          @endphp
          <ul class="list-unstyled">
            <li class="mt-2">
              <div class="d-flex align-items-center">
                <i class="bi bi-house-fill text-light"></i>
                <p class="ms-3 text-light">{{ $contact['address'] ?? '' }}</p>
              </div>
            </li>
            <li class="mt-2">
                <div class="d-flex align-items-center">
                    <i class="bi bi-telephone-inbound-fill text-light"></i>
                    <p class="ms-3">
                        <a href="tel:{{ $contact['phone'] ?? '' }}" class="text-light text-decoration-none">
                            {{ $contact['phone'] ?? '' }}
                        </a>
                    </p>
                </div>
            </li>
            <li class="mt-2">
                <div class="d-flex align-items-center">
                    <i class="bi bi-envelope-arrow-up-fill text-light"></i>
                    <p class="ms-3">
                        <a href="mailto:{{ $contact['email'] ?? '' }}" class="text-light text-decoration-none">
                            {{ $contact['email'] ?? '' }}
                        </a>
                    </p>
                </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="topbar-bg">
      <div class="container-xl text-center py-5">
        <a href="#" class="text-white">© All Rights Reserved By Medi Maniac Powered By Webglister IT</a>
      </div>
    </div>
  </footer>

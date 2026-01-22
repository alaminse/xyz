<div class="top_nav">
    <div class="nav_menu">
        <div class="nav toggle">
          <a id="menu_toggle"><i class="fa fa-bars"></i></a>
        </div>
        <nav class="nav navbar-nav">
        <ul class=" navbar-right">
          <li class="nav-item dropdown open" style="padding-left: 15px;">
            {{-- <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
              <img src="{{ getImageUrl(Auth::user()?->profile?->photo) }}" height="30px" width="30px" alt="{{ Auth::user()?->name }}">
              {{ auth()->user()->name }} <br>
                @foreach(Auth::user()?->getRoleNames() as $role)
                    <span>{{ $role }}, </span>
                @endforeach
            </a> --}}
            <a href="javascript:;" class="user-profile dropdown-toggle d-flex align-items-center" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                <img src="{{ getImageUrl(Auth::user()?->profile?->photo) }}" alt="{{ Auth::user()?->name }}" class="rounded-circle">
                <div class="ml-2">
                    <div>{{ Auth::user()?->name }}</div>
                    <small class="text-muted">{{ Auth::user()?->getRoleNames()->implode(', ') }}</small>
                </div>
            </a>

            <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
              <a class="dropdown-item"  href="{{ route('admin.profile') }}"> Profile</a>
              <a class="dropdown-item"  href="{{ route('admin.change.password') }}"> Change Password</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
              <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
            </div>
          </li>

        </ul>
      </nav>
    </div>
</div>

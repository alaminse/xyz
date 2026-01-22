<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
      <div class="navbar nav_title" style="border: 0;">
        <a href="{{ route('admin.dashboard') }}" class="site_title"> <span>{{ env('APP_NAME') }}</span></a>
      </div>

      <div class="clearfix"></div>

      <!-- menu profile quick info -->
      <div class="profile clearfix">
        <div class="profile_pic">
          <img src="{{ getImageUrl(Auth::user()?->profile?->photo) }}" height="56px" width="56px" alt="{{ auth()->user()->name }}" class="img-circle profile_img">
        </div>
        <div class="profile_info">
          <span>Welcome,</span>
          <h2>{{ auth()->user()->name }}</h2>
        </div>
      </div>
      <!-- /menu profile quick info -->

      <br />

      <!-- sidebar menu -->
      <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
        <div class="menu_section">
          <h3>General</h3>
          <ul class="nav side-menu">
            @can('admin-dashboard')
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i>Dashboard</a></li>
            @endcan
            @if (Gate::check('role-list') ||
                Gate::check('role-create') ||
                Gate::check('role-delete') ||
                Gate::check('permission-list') ||
                Gate::check('permission-create') ||
                Gate::check('permission-edit') ||
                Gate::check('permission-update') ||
                Gate::check('permission-delete') ||
                Gate::check('user-list') ||
                Gate::check('user-create') ||
                Gate::check('user-edit') ||
                Gate::check('role-edit'))
            <li><a><i class="fa fa-user"></i> User Management <span class="fa fa-chevron-down"></span></a>
              <ul class="nav child_menu">
                @can('role-list')
                    <li><a href="{{ route('admin.roles.index') }}">Role Manage</a></li>
                @endcan
                @can('user-list')
                <li><a href="{{ route('admin.users.index') }}">User Lists</a></li>
                @endcan
                @can('user-create')
                <li><a href="{{ route('admin.users.create') }}">Add User</a></li>
                @endcan
                @can('permission-list')
                <li><a href="{{ route('admin.permissions.index') }}">Permission Manage</a></li>
                @endcan
              </ul>
            </li>
            @endif
            @can('courses')
            <li><a href="{{ route('admin.courses.index') }}"><i class="fa fa-home"></i>Course</a></li>
            @endcan
            @if (Gate::check('chapters') || Gate::check('lessons'))
            <li><a><i class="fa fa-user"></i> Chapter & Lesson<span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                  @can('chapters')
                  <li><a href="{{ route('admin.chapters.index') }}">Chapter List</a></li>
                  @endcan
                  @can('lessons')
                  <li><a href="{{ route('admin.lessons.index') }}">Lesson List</a></li>
                  @endcan
                </ul>
            </li>
            @endif

            @can('notes')
            <li><a href="{{ route('admin.notes.index') }}"><i class="fa fa-home"></i>Notes</a></li>
            @endcan
            @can('flash-cards')
            <li><a href="{{ route('admin.flashs.index') }}"><i class="fa fa-home"></i>Flash Card</a></li>
            @endcan
            @can('mcqs')
            <li><a href="{{ route('admin.mcqs.index') }}"><i class="fa fa-home"></i>MCQ</a></li>
            @endcan
            @can('sbas')
            <li><a href="{{ route('admin.sbas.index') }}"><i class="fa fa-home"></i>SBA</a></li>
            @endcan
            @can('enrollments')
            <li><a href="{{ route('admin.enrolles.index') }}"><i class="fa fa-home"></i>Enrollments</a></li>
            @endcan
            @can('assessments')
            <li><a><i class="fa fa-user"></i> Assessments <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                  <li><a href="{{ route('admin.assessments.index') }}">Self Assessments</a></li>
                  <li><a href="{{ route('admin.assessments.user.progress') }}">User Progress</a></li>
                </ul>
            </li>
            @endcan
            @can('written-assessments')
            <li><a href="{{ route('admin.writtenassessments.index') }}"><i class="fa fa-home"></i>Written Assessments</a></li>
            @endcan
            @can('lecture-videos')
            <li><a href="{{ route('admin.lecturevideos.index') }}"><i class="fa fa-home"></i>Lecture Video</a></li>
            @endcan
            @can('verbal-assesmet')
            <li><a href="{{ route('admin.mockvivas.index') }}"><i class="fa fa-home"></i>Mock Viva</a></li>
            @endcan
            @can('ospe-station')
            <li><a href="{{ route('admin.ospestations.index') }}"><i class="fa fa-home"></i>OSPE Station</a></li>
            @endcan
            @can('settings')
            <li><a><i class="bi bi-gear-wide-connected"></i> Settings <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                  <li><a href="{{ route('admin.settings.logo') }}">Logo Settings</a></li>
                  <li><a href="{{ route('admin.settings.contact') }}">Contact Page</a></li>
                  <li><a href="{{ route('admin.settings.faqs') }}">Faqs Page</a></li>
                  <li><a href="{{ route('admin.settings.about') }}">About Us</a></li>
                  <li><a href="{{ route('admin.settings.terms') }}">Terms & Condition</a></li>
                  <li><a href="{{ route('admin.settings.privacy') }}">Privacy Policy</a></li>
                  <li><a href="{{ route('admin.settings.slider') }}">Slider Settings</a></li>
                </ul>
            </li>
            @endcan
          </ul>
        </div>
      </div>
      <div class="sidebar-footer hidden-small">
        <a data-toggle="tooltip" data-placement="top" title="Logout" href="{{ route('logout') }}"
            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
      </div>
    </div>
</div>

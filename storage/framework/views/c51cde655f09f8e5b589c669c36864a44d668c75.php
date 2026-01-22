<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
      <div class="navbar nav_title" style="border: 0;">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="site_title"> <span><?php echo e(env('APP_NAME')); ?></span></a>
      </div>

      <div class="clearfix"></div>

      <!-- menu profile quick info -->
      <div class="profile clearfix">
        <div class="profile_pic">
          <img src="<?php echo e(getImageUrl(Auth::user()?->profile?->photo)); ?>" height="56px" width="56px" alt="<?php echo e(auth()->user()->name); ?>" class="img-circle profile_img">
        </div>
        <div class="profile_info">
          <span>Welcome,</span>
          <h2><?php echo e(auth()->user()->name); ?></h2>
        </div>
      </div>
      <!-- /menu profile quick info -->

      <br />

      <!-- sidebar menu -->
      <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
        <div class="menu_section">
          <h3>General</h3>
          <ul class="nav side-menu">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin-dashboard')): ?>
            <li><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa fa-home"></i>Dashboard</a></li>
            <?php endif; ?>
            <?php if(Gate::check('role-list') ||
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
                Gate::check('role-edit')): ?>
            <li><a><i class="fa fa-user"></i> User Management <span class="fa fa-chevron-down"></span></a>
              <ul class="nav child_menu">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role-list')): ?>
                    <li><a href="<?php echo e(route('admin.roles.index')); ?>">Role Manage</a></li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-list')): ?>
                <li><a href="<?php echo e(route('admin.users.index')); ?>">User Lists</a></li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-create')): ?>
                <li><a href="<?php echo e(route('admin.users.create')); ?>">Add User</a></li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permission-list')): ?>
                <li><a href="<?php echo e(route('admin.permissions.index')); ?>">Permission Manage</a></li>
                <?php endif; ?>
              </ul>
            </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('courses')): ?>
            <li><a href="<?php echo e(route('admin.courses.index')); ?>"><i class="fa fa-home"></i>Course</a></li>
            <?php endif; ?>
            <?php if(Gate::check('chapters') || Gate::check('lessons')): ?>
            <li><a><i class="fa fa-user"></i> Chapter & Lesson<span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('chapters')): ?>
                  <li><a href="<?php echo e(route('admin.chapters.index')); ?>">Chapter List</a></li>
                  <?php endif; ?>
                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lessons')): ?>
                  <li><a href="<?php echo e(route('admin.lessons.index')); ?>">Lesson List</a></li>
                  <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('notes')): ?>
            <li><a href="<?php echo e(route('admin.notes.index')); ?>"><i class="fa fa-home"></i>Notes</a></li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('flash-cards')): ?>
            <li><a href="<?php echo e(route('admin.flashs.index')); ?>"><i class="fa fa-home"></i>Flash Card</a></li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('mcqs')): ?>
            <li><a href="<?php echo e(route('admin.mcqs.index')); ?>"><i class="fa fa-home"></i>MCQ</a></li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sbas')): ?>
            <li><a href="<?php echo e(route('admin.sbas.index')); ?>"><i class="fa fa-home"></i>SBA</a></li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('enrollments')): ?>
            <li><a href="<?php echo e(route('admin.enrolles.index')); ?>"><i class="fa fa-home"></i>Enrollments</a></li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('assessments')): ?>
            <li><a><i class="fa fa-user"></i> Assessments <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                  <li><a href="<?php echo e(route('admin.assessments.index')); ?>">Self Assessments</a></li>
                  <li><a href="<?php echo e(route('admin.assessments.user.progress')); ?>">User Progress</a></li>
                </ul>
            </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('written-assessments')): ?>
            <li><a href="<?php echo e(route('admin.writtenassessments.index')); ?>"><i class="fa fa-home"></i>Written Assessments</a></li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lecture-videos')): ?>
            <li><a href="<?php echo e(route('admin.lecturevideos.index')); ?>"><i class="fa fa-home"></i>Lecture Video</a></li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('verbal-assesmet')): ?>
            <li><a href="<?php echo e(route('admin.mockvivas.index')); ?>"><i class="fa fa-home"></i>Mock Viva</a></li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ospe-station')): ?>
            <li><a href="<?php echo e(route('admin.ospestations.index')); ?>"><i class="fa fa-home"></i>OSPE Station</a></li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('settings')): ?>
            <li><a><i class="bi bi-gear-wide-connected"></i> Settings <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                  <li><a href="<?php echo e(route('admin.settings.logo')); ?>">Logo Settings</a></li>
                  <li><a href="<?php echo e(route('admin.settings.contact')); ?>">Contact Page</a></li>
                  <li><a href="<?php echo e(route('admin.settings.faqs')); ?>">Faqs Page</a></li>
                  <li><a href="<?php echo e(route('admin.settings.about')); ?>">About Us</a></li>
                  <li><a href="<?php echo e(route('admin.settings.terms')); ?>">Terms & Condition</a></li>
                  <li><a href="<?php echo e(route('admin.settings.privacy')); ?>">Privacy Policy</a></li>
                  <li><a href="<?php echo e(route('admin.settings.slider')); ?>">Slider Settings</a></li>
                </ul>
            </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
      <div class="sidebar-footer hidden-small">
        <a data-toggle="tooltip" data-placement="top" title="Logout" href="<?php echo e(route('logout')); ?>"
            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
        </a>
        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
            <?php echo csrf_field(); ?>
        </form>
      </div>
    </div>
</div>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/includes/sidebar.blade.php ENDPATH**/ ?>
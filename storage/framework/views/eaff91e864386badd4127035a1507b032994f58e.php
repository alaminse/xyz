<div class="top_nav">
    <div class="nav_menu">
        <div class="nav toggle">
          <a id="menu_toggle"><i class="fa fa-bars"></i></a>
        </div>
        <nav class="nav navbar-nav">
        <ul class=" navbar-right">
          <li class="nav-item dropdown open" style="padding-left: 15px;">
            
            <a href="javascript:;" class="user-profile dropdown-toggle d-flex align-items-center" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                <img src="<?php echo e(getImageUrl(Auth::user()?->profile?->photo)); ?>" alt="<?php echo e(Auth::user()?->name); ?>" class="rounded-circle">
                <div class="ml-2">
                    <div><?php echo e(Auth::user()?->name); ?></div>
                    <small class="text-muted"><?php echo e(Auth::user()?->getRoleNames()->implode(', ')); ?></small>
                </div>
            </a>

            <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
              <a class="dropdown-item"  href="<?php echo e(route('admin.profile')); ?>"> Profile</a>
              <a class="dropdown-item"  href="<?php echo e(route('admin.change.password')); ?>"> Change Password</a>
                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                    <?php echo csrf_field(); ?>
                </form>
              <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
            </div>
          </li>

        </ul>
      </nav>
    </div>
</div>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/includes/top_nav.blade.php ENDPATH**/ ?>
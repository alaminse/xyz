<?php $__env->startSection('title', 'User Lists'); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')); ?>" rel="stylesheet">
    <style>
        .user-section {
            margin-bottom: 30px;
        }
        .section-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 8px 8px 0 0;
            margin-bottom: 0;
        }
        .card-custom {
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        .badge-paid {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
        }
        .badge-free {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        /* Fixed card dimensions */
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            text-align: center;
            height: 180px; /* Fixed height */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: all 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 10px 0;
        }
        .stats-paid {
            color: #f5576c;
        }
        .stats-free {
            color: #00f2fe;
        }
        .stats-total {
            color: #667eea;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .action-buttons form {
            display: inline-block;
        }

        /* Toggle buttons styling */
        .toggle-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            justify-content: center;
        }
        .toggle-btn {
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .toggle-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        .toggle-btn.active {
            color: white;
        }
        .toggle-btn.paid-btn {
            background: #e9ecef;
        }
        .toggle-btn.paid-btn.active {
             background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .toggle-btn.free-btn {
            background: #e9ecef;
        }
        .toggle-btn.free-btn.active {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .user-table-container {
            display: none;
        }
        .user-table-container.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Users Management</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="btn btn-sm btn-success text-white" href="<?php echo e(route('admin.users.create')); ?>">
                        <i class="fa fa-plus"></i> Add New
                    </a>
                </li>
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stats-card">
                        <i class="fa fa-users fa-2x text-muted"></i>
                        <h5>Total Users</h5>
                        <div class="stats-number stats-total"><?php echo e(count($paidUsers) + count($freeUsers)); ?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <i class="fa fa-crown fa-2x text-muted"></i>
                        <h5>Paid Users</h5>
                        <div class="stats-number stats-paid"><?php echo e(count($paidUsers)); ?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <i class="fa fa-gift fa-2x text-muted"></i>
                        <h5>Free Users</h5>
                        <div class="stats-number stats-free"><?php echo e(count($freeUsers)); ?></div>
                    </div>
                </div>
            </div>

            <!-- Toggle Buttons -->
            <div class="toggle-buttons">
                <button class="toggle-btn paid-btn active" onclick="showUserType('paid')">
                    <i class="fa fa-crown"></i>
                    Paid Users (<?php echo e(count($paidUsers)); ?>)
                </button>
                <button class="toggle-btn free-btn" onclick="showUserType('free')">
                    <i class="fa fa-gift"></i>
                    Free Users (<?php echo e(count($freeUsers)); ?>)
                </button>
            </div>

            <!-- Paid Users Table -->
            <div id="paid-users-container" class="user-table-container active">
                <div class="card card-custom">
                    <div class="section-header">
                        <h4 class="mb-0">
                            <i class="fa fa-crown"></i> Paid Users List
                        </h4>
                    </div>
                    <div class="card-body p-0">
                        <?php if(count($paidUsers) > 0): ?>
                            <div class="table-responsive">
                                <table id="paid-users-table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Joining Date</th>
                                            <th>Roles</th>
                                            <th width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $paidUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e(++$key); ?></td>
                                            <td>
                                                <?php echo e($user->name); ?>

                                            </td>
                                            <td><?php echo e($user->email); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($user->created_at)->format('M d, Y')); ?></td>
                                            <td>
                                                <?php if(!empty($user->getRoleNames())): ?>
                                                    <?php $__currentLoopData = $user->getRoleNames(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <label class="badge badge-success"><?php echo e($v); ?></label>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.users.show', $user->id)); ?>" title="View">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a class="btn btn-primary btn-sm" href="<?php echo e(route('admin.users.edit', $user->id)); ?>" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="<?php echo e(route('admin.users.destroy', $user->id)); ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button class="btn btn-danger btn-sm" type="submit" title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info m-3">
                                <i class="fa fa-info-circle"></i> No paid users found
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Free Users Table -->
            <div id="free-users-container" class="user-table-container">
                <div class="card card-custom">
                    <div class="section-header" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <h4 class="mb-0">
                            <i class="fa fa-gift"></i> Free Users List
                        </h4>
                    </div>
                    <div class="card-body p-0">
                        <?php if(count($freeUsers) > 0): ?>
                            <div class="table-responsive">
                                <table id="free-users-table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Joining Date</th>
                                            <th>Roles</th>
                                            <th width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $freeUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e(++$key); ?></td>
                                            <td>
                                                <?php echo e($user->name); ?>

                                            </td>
                                            <td><?php echo e($user->email); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($user->created_at)->format('M d, Y')); ?></td>
                                            <td>
                                                <?php if(!empty($user->getRoleNames())): ?>
                                                    <?php $__currentLoopData = $user->getRoleNames(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <label class="badge badge-success"><?php echo e($v); ?></label>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.users.show', $user->id)); ?>" title="View">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a class="btn btn-primary btn-sm" href="<?php echo e(route('admin.users.edit', $user->id)); ?>" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="<?php echo e(route('admin.users.destroy', $user->id)); ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button class="btn btn-danger btn-sm" type="submit" title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info m-3">
                                <i class="fa fa-info-circle"></i> No free users found
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
    <!-- Datatables -->
    <script src="<?php echo e(asset('backend/vendors/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('backend/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('backend/vendors/datatables.net-responsive/js/dataTables.responsive.min.js')); ?>"></script>
    <script src="<?php echo e(asset('backend/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js')); ?>"></script>

    <script>
        let paidTable = null;
        let freeTable = null;

        $(document).ready(function() {
            // Initialize DataTable for Paid Users
            paidTable = $('#paid-users-table').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search paid users..."
                },
                dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>rtip'
            });

            // Initialize DataTable for Free Users
            freeTable = $('#free-users-table').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search free users..."
                },
                dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>rtip'
            });

            // Animate statistics numbers on page load
            $('.stats-number').each(function() {
                var $this = $(this);
                var countTo = parseInt($this.text());
                $this.text('0');
                $({ countNum: 0 }).animate({
                    countNum: countTo
                }, {
                    duration: 1500,
                    easing: 'swing',
                    step: function() {
                        $this.text(Math.floor(this.countNum));
                    },
                    complete: function() {
                        $this.text(this.countNum);
                    }
                });
            });
        });

        // Function to toggle between paid and free users
        function showUserType(type) {
            const paidBtn = $('.paid-btn');
            const freeBtn = $('.free-btn');
            const paidContainer = $('#paid-users-container');
            const freeContainer = $('#free-users-container');

            if (type === 'paid') {
                // Show paid users
                paidBtn.addClass('active');
                freeBtn.removeClass('active');
                paidContainer.addClass('active');
                freeContainer.removeClass('active');

                // Redraw paid table to fix responsive issues
                if (paidTable) {
                    setTimeout(() => {
                        paidTable.columns.adjust().responsive.recalc();
                    }, 100);
                }
            } else if (type === 'free') {
                // Show free users
                freeBtn.addClass('active');
                paidBtn.removeClass('active');
                freeContainer.addClass('active');
                paidContainer.removeClass('active');

                // Redraw free table to fix responsive issues
                if (freeTable) {
                    setTimeout(() => {
                        freeTable.columns.adjust().responsive.recalc();
                    }, 100);
                }
            }
        }

        // Delete confirmation (if you want to keep the original function)
        function showDeleteConfirmation(event) {
            if (!confirm('Are you sure you want to delete this user?')) {
                event.preventDefault();
                return false;
            }
        }
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/users/index.blade.php ENDPATH**/ ?>
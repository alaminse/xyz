<?php $__env->startSection('title', 'Profile'); ?>
<?php $__env->startSection('content'); ?>
    <div class="profile-wrapper">
        <!-- Animated Background -->
        <div class="animated-bg">
            <div class="gradient-sphere sphere-1"></div>
            <div class="gradient-sphere sphere-2"></div>
            <div class="gradient-sphere sphere-3"></div>
        </div>

        <div class="container">
            <!-- Profile Header Card -->
            <div class="profile-header-card">
                <div class="header-decoration">
                    <div class="decoration-shape shape-1"></div>
                    <div class="decoration-shape shape-2"></div>
                    <div class="decoration-shape shape-3"></div>
                </div>

                <div class="row align-items-center position-relative">
                    <div class="col-lg-3 col-md-4">
                        <div class="profile-avatar-container">
                            <div class="avatar-wrapper">
                                <?php if($user->profile?->photo): ?>
                                    <img src="<?php echo e(getImageUrl($user->profile?->photo)); ?>" alt="Profile" class="avatar-image">
                                <?php else: ?>
                                    <div class="avatar-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="avatar-ring"></div>
                            </div>
                            <div class="status-indicator">
                                <span class="status-dot"></span>
                                <span class="status-text">Active</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-5">
                        <div class="profile-info">
                            <h1 class="profile-name"><?php echo e($user->name); ?> <?php echo e($user->profile?->lname); ?></h1>
                            <div class="profile-meta">
                                <div class="meta-item">
                                    <i class="fas fa-envelope"></i>
                                    <span><?php echo e($user->email); ?></span>
                                </div>
                                <?php if($user->profile?->phone): ?>
                                    <div class="meta-item">
                                        <i class="fas fa-phone"></i>
                                        <span><?php echo e($user->profile?->phone); ?></span>
                                    </div>
                                <?php endif; ?>
                                <div class="meta-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Joined <?php echo e($user->created_at->format('M Y')); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3">
                        <div class="profile-actions">
                            <button id="edit-profile-btn" class="action-btn primary-btn">
                                <i class="fas fa-edit"></i>
                                <span>Edit Profile</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Display Mode Content -->
            <div id="profile-display" class="profile-content">
                <!-- Stats Cards -->
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-icon bg-gradient-1">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Active</h3>
                            <p>Account Status</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon bg-gradient-2">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo e($user->profile?->blood_group ?? 'N/A'); ?></h3>
                            <p>Blood Group</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon bg-gradient-3">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo e(ucfirst($user->profile?->gender ?? 'Not Set')); ?></h3>
                            <p>Gender</p>
                        </div>
                    </div>
                </div>

                <!-- Information Cards -->
                <div class="info-cards-container">
                    <!-- Personal Info Card -->
                    <div class="info-card">
                        <div class="card-header-custom">
                            <div class="header-icon">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <h3>Personal Information</h3>
                            <div class="header-decoration"></div>
                        </div>
                        <div class="card-body-custom">
                            <div class="info-grid">
                                <div class="info-field">
                                    <label>Full Name</label>
                                    <div class="field-value">
                                        <?php echo e($user->name); ?> <?php echo e($user->profile?->lname ?? ''); ?>

                                    </div>
                                </div>
                                <div class="info-field">
                                    <label>Date of Birth</label>
                                    <div class="field-value">
                                        <?php echo e($user->profile?->dob ?? 'Not provided'); ?>

                                    </div>
                                </div>
                                <div class="info-field">
                                    <label>Gender</label>
                                    <div class="field-value">
                                        <?php echo e(ucfirst($user->profile?->gender ?? 'Not specified')); ?>

                                    </div>
                                </div>
                                <div class="info-field">
                                    <label>Blood Group</label>
                                    <div class="field-value">
                                        <?php if($user->profile?->blood_group): ?>
                                            <span class="blood-badge"><?php echo e($user->profile->blood_group); ?></span>
                                        <?php else: ?>
                                            Not provided
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info Card -->
                    <div class="info-card">
                        <div class="card-header-custom">
                            <div class="header-icon">
                                <i class="fas fa-address-book"></i>
                            </div>
                            <h3>Contact Information</h3>
                            <div class="header-decoration"></div>
                        </div>
                        <div class="card-body-custom">
                            <div class="info-grid">
                                <div class="info-field">
                                    <label>Email Address</label>
                                    <div class="field-value">
                                        <i class="fas fa-envelope field-icon"></i>
                                        <?php echo e($user->email); ?>

                                    </div>
                                </div>
                                <div class="info-field">
                                    <label>Phone Number</label>
                                    <div class="field-value">
                                        <i class="fas fa-phone field-icon"></i>
                                        <?php echo e($user->profile?->phone ?? 'Not provided'); ?>

                                    </div>
                                </div>
                                <div class="info-field">
                                    <label>Account Type</label>
                                    <div class="field-value">
                                        <i class="fas fa-user-tag field-icon"></i>
                                        Premium Member
                                    </div>
                                </div>
                                <div class="info-field">
                                    <label>Member Since</label>
                                    <div class="field-value">
                                        <i class="fas fa-calendar field-icon"></i>
                                        <?php echo e($user->created_at->format('F d, Y')); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Mode Content -->
            <div id="profile-edit" class="profile-content" style="display: none;">
                <div class="edit-form-container">
                    <div class="form-header">
                        <h2><i class="fas fa-user-edit"></i> Edit Profile Information</h2>
                        <p>Update your personal details and contact information</p>
                    </div>

                    <form action="<?php echo e(route('update.profile', Auth::id())); ?>" method="post" enctype="multipart/form-data" class="modern-form">
                        <?php echo csrf_field(); ?>
                        <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        <!-- Personal Information -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-user"></i>
                                <span>Personal Information</span>
                            </div>
                            <div class="form-grid">
                                <div class="form-group-modern">
                                    <label class="floating-label">First Name</label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-user input-icon"></i>
                                        <input type="text" class="modern-input" name="name" value="<?php echo e($user->name); ?>" required>
                                    </div>
                                </div>
                                <div class="form-group-modern">
                                    <label class="floating-label">Last Name</label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-user input-icon"></i>
                                        <input type="text" class="modern-input" name="lname" value="<?php echo e($user->profile?->lname); ?>">
                                    </div>
                                </div>
                                <div class="form-group-modern">
                                    <label class="floating-label">Date of Birth</label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-calendar input-icon"></i>
                                        <input type="date" class="modern-input" name="dob" value="<?php echo e($user->profile?->dob); ?>">
                                    </div>
                                </div>
                                <div class="form-group-modern">
                                    <label class="floating-label">Gender</label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-venus-mars input-icon"></i>
                                        <select name="gender" class="modern-input">
                                            <option value="">Select Gender</option>
                                            <?php echo generateGenderOptions(old('gender', $user->profile?->gender ?? null)); ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group-modern">
                                    <label class="floating-label">Blood Group</label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-tint input-icon"></i>
                                        <select name="blood_group" class="modern-input">
                                            <option value="">Select Blood Group</option>
                                            <?php echo generateBloodGroupOptions(old('blood_group', $user->profile?->blood_group ?? null)); ?>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-address-book"></i>
                                <span>Contact Information</span>
                            </div>
                            <div class="form-grid">
                                <div class="form-group-modern">
                                    <label class="floating-label">Email Address</label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-envelope input-icon"></i>
                                        <input type="email" class="modern-input" name="email" value="<?php echo e($user->email); ?>" required>
                                    </div>
                                </div>
                                <div class="form-group-modern">
                                    <label class="floating-label">Phone Number</label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-phone input-icon"></i>
                                        <input type="tel" class="modern-input" name="phone" value="<?php echo e($user->profile?->phone); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Photo -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-camera"></i>
                                <span>Profile Photo</span>
                            </div>
                            <div class="photo-upload-section">
                                <div class="current-photo">
                                    <img id="preview-image" src="<?php echo e(getImageUrl($user->profile?->photo)); ?>" alt="Profile">
                                    <div class="photo-overlay">
                                        <i class="fas fa-camera"></i>
                                    </div>
                                </div>
                                <div class="upload-controls">
                                    <label for="photo" class="upload-btn">
                                        <i class="fas fa-upload"></i>
                                        Choose New Photo
                                    </label>
                                    <input type="file" name="photo" id="photo" class="hidden-input">
                                    <p class="upload-hint">JPG, PNG or GIF (MAX. 5MB)</p>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="submit" class="submit-btn">
                                <i class="fas fa-save"></i>
                                Save Changes
                            </button>
                            <button type="button" id="cancel-edit-btn" class="cancel-btn">
                                <i class="fas fa-times"></i>
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Animated Background - Fixed to not cover sidebar */
        .profile-wrapper {
            min-height: 100vh;
            position: relative;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem 0;
            /* Ensure it doesn't interfere with sidebar */
            margin-left: 0;
            width: 100%;
        }

        .animated-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
            /* Prevent interaction with background */
            pointer-events: none;
        }

        .gradient-sphere {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.3;
            animation: float 20s infinite ease-in-out;
            /* Prevent interaction with spheres */
            pointer-events: none;
        }

        .sphere-1 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            top: -200px;
            right: -200px;
            animation-delay: 0s;
        }

        .sphere-2 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            bottom: -150px;
            left: -150px;
            animation-delay: 5s;
        }

        .sphere-3 {
            width: 350px;
            height: 350px;
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: 10s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(30px, -30px) scale(1.1); }
            50% { transform: translate(-30px, 30px) scale(0.9); }
            75% { transform: translate(30px, 30px) scale(1.05); }
        }

        /* Main Container */
        .container {
            position: relative;
            z-index: 1;
            /* Ensure content is above background but below sidebar */
            z-index: 10;
        }

        /* Profile Header Card */
        .profile-header-card {
            margin-bottom: 1rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
        }

        .header-decoration {
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .decoration-shape {
            position: absolute;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            opacity: 0.05;
            pointer-events: none;
        }

        .shape-1 {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            top: -100px;
            right: -50px;
        }

        .shape-2 {
            width: 150px;
            height: 150px;
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            bottom: -50px;
            right: 100px;
            animation: morph 8s ease-in-out infinite;
        }

        .shape-3 {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            top: 50%;
            left: -50px;
        }

        @keyframes morph {
            0%, 100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
            50% { border-radius: 70% 30% 30% 70% / 70% 70% 30% 30%; }
        }

        /* Profile Avatar */
        .profile-avatar-container {
            text-align: center;
            position: relative;
        }

        .avatar-wrapper {
            position: relative;
            display: inline-block;
        }

        .avatar-image, .avatar-placeholder {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            border: 6px solid transparent;
            background: linear-gradient(white, white) padding-box,
                        linear-gradient(135deg, #667eea 0%, #764ba2 100%) border-box;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        }

        .avatar-placeholder {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 4rem;
        }

        .avatar-ring {
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            border: 3px solid transparent;
            border-radius: 50%;
            background: linear-gradient(45deg, #f093fb, #f5576c, #4facfe, #00f2fe, #43e97b, #38f9d7);
            background-size: 300% 300%;
            animation: rotate 3s linear infinite;
            z-index: -1;
            pointer-events: none;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
            padding: 8px 16px;
            background: rgba(76, 175, 80, 0.1);
            border-radius: 20px;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            background: #4caf50;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .status-text {
            font-size: 0.9rem;
            font-weight: 600;
            color: #4caf50;
        }

        /* Profile Info */
        .profile-info {
            padding: 0 2rem;
        }

        .profile-name {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
        }

        .profile-meta {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #666;
            font-size: 1rem;
        }

        .meta-item i {
            color: #667eea;
            width: 20px;
        }

        /* Action Buttons */
        .profile-actions {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 15px 25px;
            border-radius: 15px;
            border: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .primary-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .primary-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
        }

        .secondary-btn {
            background: white;
            color: #667eea;
            border: 2px solid #e9ecef;
        }

        .secondary-btn:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
        }

        /* Stats Cards */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 0.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .bg-gradient-1 { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .bg-gradient-2 { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .bg-gradient-3 { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }

        .stat-info h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-info p {
            color: #666;
            font-size: 0.9rem;
            margin: 0;
        }

        /* Info Cards */
        .info-cards-container {
            padding-bottom: 1rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
        }

        .info-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
        }

        .card-header-custom {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .header-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            margin-bottom: 1rem;
        }

        .card-header-custom h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #333;
            margin: 0;
        }

        .header-decoration {
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .card-body-custom {
            padding: 2rem;
        }

        .info-grid {
            display: grid;
            gap: 1.5rem;
        }

        .info-field {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .info-field:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            transform: translateX(5px);
        }

        .info-field label {
            display: block;
            font-size: 0.85rem;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .field-value {
            font-size: 1.1rem;
            color: #333;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .field-icon {
            color: #667eea;
            font-size: 0.9rem;
        }

        .blood-badge {
            display: inline-block;
            padding: 5px 15px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border-radius: 20px;
            font-weight: 600;
        }

        /* Edit Form */
        .edit-form-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .form-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .form-header h2 {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: #666;
            font-size: 1rem;
        }

        .modern-form {
            max-width: 800px;
            margin: 0 auto;
        }

        .form-section {
            margin-bottom: 3rem;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e9ecef;
        }

        .section-title i {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .section-title span {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .form-group-modern {
            position: relative;
        }

        .floating-label {
            position: absolute;
            top: -10px;
            left: 15px;
            background: white;
            padding: 0 10px;
            font-size: 0.9rem;
            font-weight: 600;
            color: #667eea;
            z-index: 1;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
            z-index: 1;
        }

        .modern-input {
            width: 100%;
            padding: 18px 20px 18px 50px;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .modern-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        /* Photo Upload */
        .photo-upload-section {
            display: flex;
            align-items: center;
            gap: 3rem;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 20px;
        }

        .current-photo {
            position: relative;
            width: 150px;
            height: 150px;
        }

        .current-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 20px;
            border: 4px solid white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .photo-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(102, 126, 234, 0.8);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .current-photo:hover .photo-overlay {
            opacity: 1;
        }

        .photo-overlay i {
            color: white;
            font-size: 2rem;
        }

        .upload-controls {
            flex: 1;
        }

        .upload-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .hidden-input {
            display: none;
        }

        .upload-hint {
            margin-top: 10px;
            color: #666;
            font-size: 0.9rem;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 3rem;
        }

        .submit-btn, .cancel-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px 40px;
            border-radius: 15px;
            font-weight: 600;
            font-size: 1.1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .submit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
        }

        .cancel-btn {
            background: white;
            color: #667eea;
            border: 2px solid #e9ecef;
        }

        .cancel-btn:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .profile-header-card {
                padding: 2rem 1.5rem;
            }

            .profile-name {
                font-size: 1.8rem;
            }

            .stats-row {
                grid-template-columns: 1fr;
            }

            .info-cards-container {
                grid-template-columns: 1fr;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .photo-upload-section {
                flex-direction: column;
                text-align: center;
            }

            .form-actions {
                flex-direction: column;
            }
        }
    </style>


    <?php $__env->startPush('scripts'); ?>
        <script>
            $(document).ready(function() {
                // Toggle between display and edit modes
                const editBtn = $('#edit-profile-btn');
                const cancelBtn = $('#cancel-edit-btn');
                const displayMode = $('#profile-display');
                const editMode = $('#profile-edit');

                editBtn.on('click', function() {
                    displayMode.fadeOut(300, function() {
                        editMode.fadeIn(300);
                    });
                    $('html, body').animate({
                        scrollTop: 0
                    }, 300);
                });

                cancelBtn.on('click', function() {
                    editMode.fadeOut(300, function() {
                        displayMode.fadeIn(300);
                    });
                });

                // Photo preview
                $('#photo').on('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $('#preview-image').attr('src', e.target.result);
                        }
                        reader.readAsDataURL(file);
                    }
                });

                // Add entrance animations
                $('.stat-card, .info-card').each(function(index) {
                    $(this).css('opacity', '0');
                    $(this).css('transform', 'translateY(30px)');
                    setTimeout(() => {
                        $(this).animate({
                            opacity: 1,
                            transform: 'translateY(0)'
                        }, 600);
                    }, index * 100);
                });
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/settings/profile.blade.php ENDPATH**/ ?>
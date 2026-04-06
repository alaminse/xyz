<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'Medi Maniac'); ?></title>

    <link href="<?php echo e(asset('frontend/bootstrap-5.3.3/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy:       #0d1b2a;
            --teal:       #0a9396;
            --teal-light: #94d2bd;
            --cream:      #f8f5f0;
            --white:      #ffffff;
            --muted:      #6c757d;
            --danger:     #e63946;
            --card-bg:    rgba(255,255,255,0.72);
            --shadow:     0 20px 60px rgba(13,27,42,0.12);
            --radius:     18px;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            min-height: 100vh;
            margin: 0;
        }

        /* ── Navbar ── */
        .mm-navbar {
            background: var(--navy);
            padding: 0 1.5rem;
            height: 64px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 20px rgba(13,27,42,0.25);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .mm-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.45rem;
            font-weight: 700;
            color: var(--white);
            letter-spacing: 0.02em;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .mm-brand-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: var(--teal);
            display: inline-block;
        }

        /* ── Page background ── */
        .mm-page {
            min-height: calc(100vh - 64px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
            background:
                radial-gradient(ellipse 70% 50% at 10% 20%, rgba(10,147,150,0.08) 0%, transparent 60%),
                radial-gradient(ellipse 60% 40% at 90% 80%, rgba(148,210,189,0.1) 0%, transparent 55%),
                var(--cream);
        }

        /* ── Auth Card ── */
        .mm-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.65);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            width: 100%;
            max-width: 480px;
        }

        .mm-card-accent {
            height: 5px;
            background: linear-gradient(90deg, var(--teal) 0%, var(--teal-light) 100%);
        }

        .mm-card-body {
            padding: 2.5rem 2.75rem;
        }
        @media (max-width: 480px) {
            .mm-card-body { padding: 2rem 1.5rem; }
        }

        /* ── Typography ── */
        .mm-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--navy);
            margin: 0 0 0.35rem;
            line-height: 1.2;
        }
        .mm-subtitle {
            font-size: 0.9rem;
            color: var(--muted);
            margin: 0 0 2rem;
        }

        /* ── Form Elements ── */
        .mm-label {
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--navy);
            margin-bottom: 0.45rem;
            display: block;
        }
        .mm-input {
            width: 100%;
            padding: 0.75rem 1.1rem;
            border: 1.5px solid #dde3ea;
            border-radius: 10px;
            background: rgba(255,255,255,0.8);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            color: var(--navy);
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            display: block;
        }
        .mm-input:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 3.5px rgba(10,147,150,0.13);
            background: #fff;
        }
        .mm-input.is-invalid {
            border-color: var(--danger);
        }
        .mm-input.is-invalid:focus {
            box-shadow: 0 0 0 3.5px rgba(230,57,70,0.13);
        }
        .mm-invalid-msg {
            font-size: 0.8rem;
            color: var(--danger);
            margin-top: 0.35rem;
        }
        .mm-field { margin-bottom: 1.35rem; }

        /* ── Buttons ── */
        .mm-btn {
            display: block;
            width: 100%;
            padding: 0.82rem 1.5rem;
            background: var(--teal);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: 0.03em;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 18px rgba(10,147,150,0.28);
            margin-top: 0.5rem;
            text-align: center;
        }
        .mm-btn:hover {
            background: #078080;
            transform: translateY(-1px);
            box-shadow: 0 6px 22px rgba(10,147,150,0.36);
        }
        .mm-btn:active { transform: translateY(0); }

        /* ── Links ── */
        .mm-link {
            color: var(--teal);
            text-decoration: none;
            font-weight: 500;
        }
        .mm-link:hover { text-decoration: underline; }

        .mm-link-btn {
            background: none;
            border: none;
            padding: 0;
            color: var(--teal);
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            font-size: inherit;
            cursor: pointer;
            text-decoration: none;
        }
        .mm-link-btn:hover { text-decoration: underline; }

        /* ── Alerts ── */
        .mm-alert {
            padding: 0.85rem 1.1rem;
            border-radius: 10px;
            font-size: 0.88rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
        }
        .mm-alert-success {
            background: rgba(10,147,150,0.1);
            border: 1px solid rgba(10,147,150,0.25);
            color: #056668;
        }
        .mm-alert-icon { font-size: 1rem; flex-shrink: 0; margin-top: 1px; }

        /* ── Divider ── */
        .mm-divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 1.5rem 0;
            color: #bcc5ce;
            font-size: 0.8rem;
        }
        .mm-divider::before, .mm-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e0e6eb;
        }

        /* ── Footer text ── */
        .mm-footer-text {
            text-align: center;
            font-size: 0.85rem;
            color: var(--muted);
            margin-top: 1.5rem;
        }

        /* ── Icon badge ── */
        .mm-icon-badge {
            width: 52px; height: 52px;
            background: linear-gradient(135deg, var(--teal) 0%, var(--teal-light) 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            box-shadow: 0 6px 18px rgba(10,147,150,0.28);
        }
        .mm-icon-badge svg { width: 26px; height: 26px; fill: #fff; }
    </style>
</head>
<body>

    <nav class="mm-navbar">
        <a class="mm-brand" href="/">
            <span class="mm-brand-dot"></span>
            Medi Maniac
        </a>
    </nav>

    <div class="mm-page">
        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <script src="<?php echo e(asset('frontend/bootstrap-5.3.3/js/jquery-3.7.1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('frontend/bootstrap-5.3.3/js/bootstrap.min.js')); ?>"></script>
</body>
</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/layouts/app.blade.php ENDPATH**/ ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Tile Management System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --dark: #111827;
            --muted: #64748b;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Inter, "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at 10% 10%, #dbeafe 0, transparent 35%),
                radial-gradient(circle at 90% 90%, #ede9fe 0, transparent 35%),
                #f8fafc;
            color: var(--dark);
        }

        .login-layout {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px 16px;
        }

        .login-card {
            width: 100%;
            max-width: 460px;
            background: #fff;
            border: 1px solid #e8edf5;
            border-radius: 24px;
            padding: 34px;
            box-shadow: 0 24px 70px rgba(15, 23, 42, .10);
        }

        .brand-icon {
            width: 66px;
            height: 66px;
            border-radius: 20px;
            display: grid;
            place-items: center;
            margin-bottom: 20px;
            color: white;
            font-size: 27px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            box-shadow: 0 10px 24px rgba(79, 70, 229, .25);
        }

        .subtitle { color: var(--muted); }

        .demo-box {
            background: #eef2ff;
            border: 1px solid #c7d2fe;
            border-radius: 14px;
            padding: 15px;
            margin: 22px 0;
            font-size: 14px;
        }

        .demo-value {
            color: #3730a3;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .form-label {
            font-weight: 600;
            font-size: 14px;
        }

        .input-group-text,
        .form-control {
            min-height: 49px;
            border-color: #dbe2ec;
        }

        .input-group-text {
            background: #f8fafc;
            color: #64748b;
        }

        .form-control:focus {
            border-color: #818cf8;
            box-shadow: 0 0 0 .2rem rgba(99, 102, 241, .12);
        }

        .login-btn {
            min-height: 50px;
            border: 0;
            border-radius: 12px;
            color: white;
            font-weight: 700;
            background: linear-gradient(100deg, #4f46e5, #7c3aed);
            transition: transform .2s, box-shadow .2s;
        }

        .login-btn:hover {
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(79, 70, 229, .25);
        }

        .footer-note {
            color: #94a3b8;
            font-size: 12px;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 25px 20px;
                border-radius: 19px;
            }
        }
    </style>
</head>

<body>
<div class="login-layout">
    <main class="login-card">

        <div class="brand-icon">
            <i class="fa-solid fa-cubes-stacked"></i>
        </div>

        <div class="text-uppercase small fw-bold text-primary mb-2">
            Inventory & Billing
        </div>

        <h1 class="h3 fw-bold mb-2">Welcome back!</h1>

        <p class="subtitle mb-0">
            Sign in to explore the Tile Management System.
        </p>

        <!-- Public demo credentials -->
        <div class="demo-box">
            <div class="fw-bold mb-2">
                <i class="fa-solid fa-circle-info me-2"></i>
                Public Demo Login
            </div>

            <div class="d-flex justify-content-between gap-2 mb-2">
                <span>Username</span>
                <span class="demo-value">demo</span>
            </div>

            <div class="d-flex justify-content-between align-items-start gap-2">
                <span>Password</span>
                <span class="demo-value text-end">TileDemo@2026</span>
            </div>

            <button type="button" id="fillDemo"
                    class="btn btn-sm btn-outline-primary mt-3 w-100">
                <i class="fa-solid fa-wand-magic-sparkles me-1"></i>
                Use Demo Credentials
            </button>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2 small" role="alert">
                <i class="fa-solid fa-circle-exclamation me-1"></i>
                <?= html_escape($error) ?>
            </div>
        <?php endif; ?>

        <form method="post"
              action="<?= site_url('Auth/login') ?>"
              autocomplete="on">

            <?php if (config_item('csrf_protection')): ?>
                <input type="hidden"
                       name="<?= $this->security->get_csrf_token_name() ?>"
                       value="<?= $this->security->get_csrf_hash() ?>">
            <?php endif; ?>

            <!-- Demo Username -->
<div class="mb-3">
    <label for="username" class="form-label">Username</label>

    <input
        type="text"
        name="username"
        id="username"
        class="form-control"
        value="demo"
        autocomplete="username"
        required
    >
</div>

<!-- Demo Password -->
<div class="mb-3">
    <label for="password" class="form-label">Password</label>

    <input
        type="password"
        name="password"
        id="password"
        class="form-control"
        value="TileDemo@2026"
        autocomplete="current-password"
        required
    >
</div>

            <button type="submit" class="btn login-btn w-100">
                Sign In <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i>
            </button>
        </form>

        <div class="text-center footer-note mt-4">
            <i class="fa-solid fa-shield-halved me-1"></i>
            Tile Management System · Portfolio Demo
        </div>
    </main>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const toggleButton = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');

    toggleButton.addEventListener('click', function () {
        const show = passwordInput.type === 'password';

        passwordInput.type = show ? 'text' : 'password';
        eyeIcon.className = show
            ? 'fa-regular fa-eye-slash'
            : 'fa-regular fa-eye';

        toggleButton.setAttribute(
            'aria-label',
            show ? 'Hide password' : 'Show password'
        );
    });

    document.getElementById('fillDemo').addEventListener('click', function () {
        document.getElementById('username').value = 'demo';
        passwordInput.value = 'TileDemo@2026';
    });
</script>
</body>
</html>

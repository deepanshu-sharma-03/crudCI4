<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">

    <div class="card p-4 shadow mx-auto" style="max-width:400px;">
        <h3 class="text-center mb-4">Login</h3>
        <?php if(session()->getFlashdata('error')){ ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php } ?>

        <form method="post" autocomplete="off" action="<?= base_url('loginUser') ?>">
            <input type="email"
                   name="email"
                   class="form-control mb-3"
                   placeholder="Email"
                   required >
            <input type="password"
                   name="password"
                   class="form-control mb-3"
                   placeholder="Password"
                   required>
            <button class="btn btn-primary w-100">
                Login
            </button>
        </form>
        <a href="<?= base_url('google-login') ?>"
            class="btn btn-primary w-100 mt-2">
            Sign Up With Google
        </a>

        <a href="<?= base_url('register') ?>" class="mt-3 text-center">
            Create Account
        </a>
    </div>
</div>
</body>
</html>
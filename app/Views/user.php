<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#eef5ff;
        }

        .dashboard-card{
            max-width:500px;
            margin:auto;
            margin-top:80px;
            border-radius:10px;
        }

        .profile-title{
            color:#0d6efd;
            font-weight:bold;
        }

        .top-bar{
            display:flex;
            justify-content:flex-end;
            padding:20px;
        }
    </style>
</head>
<body>
<div style="position: fixed !important; top: 10px !important; left: 10px !important;">

    <a href="<?= base_url('logout') ?>" class="btn btn-danger">
        Logout
    </a>

</div>

<!-- USER CARD -->
<div class="card shadow p-4 dashboard-card">
    <h3 class="text-center profile-title mb-4">
        User Dashboard
    </h3>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td><?= session()->get('id') ?></td>
        </tr>
        <tr>
            <th>Name</th>
            <td><?= session()->get('name') ?></td>
        </tr>
        <tr>
            <th>Mobile No.</th>
            <td><?= session()->get('mobile_number') ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?= session()->get('email') ?></td>
        </tr>
        <tr>
            <th>Role</th>
            <td><?= session()->get('role') ?></td>
        </tr>
    </table>
</div>
</body>
</html>
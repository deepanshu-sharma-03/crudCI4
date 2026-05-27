<!DOCTYPE html>
<html>

<head>
    <title>User Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            background: #f4f7fc;
            font-family: Arial, sans-serif;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .menu-item {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;

            padding: 12px 18px;
            border: none;
            border-radius: 10px;

            background: transparent;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;

            cursor: pointer;
            transition: all 0.3s ease;
        }

        .menu-item:hover {
            background: #374151;
            transform: translateX(5px);
        }

        /* SIDEBAR */

        .sidebar {
            width: 260px;
            height: 100vh;
            background: #1f2937;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            padding: 25px;
        }

        .profile-section {
            text-align: center;
            margin-bottom: 35px;
        }

        .profile-img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 3px solid #fff;
            object-fit: cover;
        }

        .profile-name {
            margin-top: 10px;
            font-size: 20px;
            font-weight: bold;
        }

        .menu a {
            display: block;
            color: #d1d5db;
            text-decoration: none;
            padding: 14px;
            border-radius: 10px;
            margin-bottom: 10px;
            transition: .3s;
        }

        .menu a:hover {
            background: #374151;
            color: white;
        }

        .logout-box {
            margin-top: auto;
        }

        .main-content {
            margin-left: 280px;
            padding: 40px;
        }

        .card-box {
            border: none;
            border-radius: 20px;
            padding: 30px;
        }

        .table td {
            padding: 16px !important;
        }
    </style>
</head>

<body>

    <div class="sidebar">

        <div class="profile-section">

            <img
                src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                class="profile-img">

            <div class="profile-name" id="user_name">
                User
            </div>

        </div>

        <div class="menu">

            <a href="#" class="menu-item">🏠 Dashboard</a>

            <a href="user/view-products" class="menu-item">
                🛍 Products
            </a>

            <button id="ordersBtn" class="menu-item" onclick="orderSection()">
                🛒 Orders
            </button>

            <a href="#" onclick="openEditForm()" class="menu-item">
                ✏ Edit Profile
            </a>

        </div>

        <div class="logout-box">

            <button
                onclick="logoutUser()"
                class="btn btn-danger w-100">

                Logout

            </button>

        </div>

    </div>

    <div class="main-content">

        <div class="card shadow card-box" id="dashboard">

            <h2 class="mb-4">
                My Profile
            </h2>

            <table class="table table-bordered">

                <tr>
                    <th>User ID</th>
                    <td id="user_id"></td>
                </tr>

                <tr>
                    <th>Email</th>
                    <td id="user_email"></td>
                </tr>

                <tr>
                    <th>Mobile</th>
                    <td id="user_mobile_number"></td>
                </tr>

            </table>

        </div>

        <div
            id="editBox"
            style="display:none;"
            class="card shadow p-4 mt-4">

            <h3>Edit Profile</h3>

            <input
                type="text"
                id="edit_name"
                class="form-control mb-3"
                placeholder="Name">

            <input
                type="email"
                id="edit_email"
                class="form-control mb-3"
                placeholder="Email">

            <input
                type="text"
                id="edit_mobile"
                class="form-control mb-3"
                placeholder="Mobile">

            <input
                type="password"
                id="edit_password"
                class="form-control mb-3"
                placeholder="Password">

            <button
                class="btn btn-success"
                onclick="updateProfile()">

                Save

            </button>

        </div>

    </div>
    <script>
        let base = "<?= base_url() ?>";
        // SHOW USER DATA
        loadProfile();

        function loadProfile() {
            fetch(
                    base + "get-user-profile", {
                        credentials: "include"
                    }
                )
                .then(res => res.json())
                .then(user => {
                    document.getElementById("user_id").innerHTML = user.id;
                    document.getElementById("user_name").innerHTML = user.name;
                    document.getElementById("user_email").innerHTML = user.email;
                    document.getElementById("user_mobile_number").innerHTML = user.mobile_number;

                    // EDIT FORM
                    document.getElementById("edit_name").value = user.name;
                    document.getElementById("edit_email").value = user.email;
                    document.getElementById("edit_mobile").value = user.mobile_number;
                    document.getElementById("edit_password").value = "";
                });
        }

        function openEditForm() {
            document.getElementById("dashboard").style.display = "none";
            document.getElementById("editBox").style.display = "block";
        }

        function updateProfile() {
            let id = document.getElementById("user_id").value;
            let name = document.getElementById("edit_name").value;
            let email = document.getElementById("edit_email").value;
            let mobile = document.getElementById("edit_mobile").value;
            let password = document.getElementById("edit_password").value;
            console.log(id);
            fetch("<?= base_url('update-user-profile') ?>", {
                    method: "POST",
                    credentials: "include",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: new URLSearchParams({
                        id: id,
                        name: name,
                        email: email,
                        mobile_number: mobile,
                        password: password,
                    })
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    location.reload();
                });
        }

        function logoutUser() {
            window.location.href =
                base + "logout";
        }
        // close Edit Form
        function closeEditForm() {
            document.getElementById('editBox').style.display = "none";
            document.getElementById('dashboard').style.display = "block";
        }
        // order Section
        function orderSection() {
            window.location.href =
                base + "user/orders";
        }
    </script>
</body>

</html>
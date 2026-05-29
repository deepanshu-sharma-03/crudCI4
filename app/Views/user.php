<!DOCTYPE html>
<html>

<head>
    <title>User Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

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

        /* NOTIFICATION BAR */

        #notificationStrip {
            background: linear-gradient(90deg, #2563eb, #1d4ed8);
            color: #fff;

            display: flex;
            align-items: center;
            gap: 18px;

            padding: 14px 20px;
            border-radius: 14px;

            margin-bottom: 25px;

            box-shadow:
                0 8px 20px rgba(37, 99, 235, .18);

            overflow: hidden;
        }

        .notify-icon {
            font-size: 16px;
            font-weight: 700;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .ticker-wrap {
            width: 100%;
            overflow: hidden;
        }

        .ticker {
            display: inline-block;
            white-space: nowrap;
            animation: tickerMove 22s linear infinite;
        }

        .notification-item {
            display: inline-block;
            margin-right: 90px;
            font-size: 15px;
        }

        .notification-title {
            color: #ffd54f;
            font-weight: 700;
        }

        @keyframes tickerMove {

            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
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
            <div class="form-check form-switch text-white mt-3">

                <input
                    class="form-check-input"
                    type="checkbox"
                    id="notificationToggle"
                    onchange="toggleNotification()">

                <label class="form-check-label">
                    Show Notifications
                </label>

            </div>
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

        <div id="notificationStrip">

            <span class="notify-icon">
                🔔 Notifications
            </span>

            <div class="ticker-wrap">

                <div id="notificationContent" class="ticker">

                </div>

            </div>

        </div>

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
                    document.getElementById("user_id").innerHTML = user.userData.id;
                    document.getElementById("user_name").innerHTML = user.userData.name;
                    document.getElementById("user_email").innerHTML = user.userData.email;
                    document.getElementById("user_mobile_number").innerHTML = user.userData.mobile_number;

                    // EDIT FORM
                    document.getElementById("edit_name").value = user.userData.name;
                    document.getElementById("edit_email").value = user.userData.email;
                    document.getElementById("edit_mobile").value = user.userData.mobile_number;
                    document.getElementById("edit_password").value = "";
                    // SET TOGGLE STATUS
                    let toggle = document.getElementById("notificationToggle");
                    let html = "";
                    user.nfData.forEach(n => {
                        html += `
                <span class="notification-item">
                    <span class="notification-title">
                        ${n.title}
                    </span>
                    — ${n.description}
                </span>
            `;
                    });
                    document.getElementById("notificationContent").innerHTML = html;


                    if (user.userData.nf_status == 1) {

                        toggle.checked = true;

                        document.getElementById(
                            "notificationStrip"
                        ).style.display = "flex";

                    } else {

                        toggle.checked = false;

                        document.getElementById(
                            "notificationStrip"
                        ).style.display = "none";
                    }
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

        function toggleNotification() {
            let toggle = document.getElementById("notificationToggle");
            let box = document.getElementById("notificationStrip");
            console.log(toggle.checked);
            $.ajax({
                url: "<?= base_url('/user/notification-status') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    'nfstatus': toggle.checked,
                },
                success: function(response) {
                    Swal.fire({
                        title: "Notification Status Updated!!",
                        icon: "success",
                        draggable: true
                    });
                },
                error: function(err) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "something went wrong",
                    });
                }
            });
            if (toggle.checked) {
                box.style.display = "flex";
            } else {
                box.style.display = "none";
            }
        }
    </script>
</body>

</html>
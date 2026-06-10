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

        .notification-toggle-sidebar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 14px;
            border-radius: 10px;
            margin-bottom: 10px;
            background: transparent;
            color: #d1d5db;
            cursor: pointer;
            transition: .3s;
        }

        .notification-toggle-sidebar:hover {
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

        /* NOTIFICATION HEADER */
        .header-section {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 30px;
            gap: 20px;
        }

        .notification-toggle-wrapper {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.95rem;
            color: #334155;
        }

        .toggle-switch {
            position: relative;
            width: 52px;
            height: 28px;
            display: inline-block;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: #cbd5e1;
            border-radius: 999px;
            transition: 0.3s;
        }

        .slider:before {
            content: "";
            position: absolute;
            left: 4px;
            top: 4px;
            width: 20px;
            height: 20px;
            background-color: white;
            border-radius: 50%;
            transition: 0.3s;
        }

        .toggle-switch input:checked+.slider {
            background-color: #2563eb;
        }

        .toggle-switch input:checked+.slider:before {
            transform: translateX(24px);
        }

        .notification-btn {
            position: relative;
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .notification-btn:hover {
            transform: scale(1.1);
        }

        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }

        /* NOTIFICATION PANEL */
        .notification-panel {
            position: fixed;
            top: 0;
            right: 0;
            width: 400px;
            height: 100vh;
            background: white;
            box-shadow: -4px 0 12px rgba(0, 0, 0, 0.15);
            transform: translateX(100%);
            transition: transform 0.3s ease;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        .notification-panel.active {
            transform: translateX(0);
        }

        .notification-panel-header {
            padding: 20px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-panel-title {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }

        .close-panel-btn {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .close-panel-btn:hover {
            transform: scale(1.2);
        }

        .notification-panel-content {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
        }

        .notification-item-card {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 12px;
            position: relative;
            display: flex;
            gap: 12px;
            transition: all 0.3s ease;
        }

        .notification-item-card:hover {
            background: #e9ecef;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .notification-icon {
            font-size: 24px;
            flex-shrink: 0;
        }

        .notification-content {
            flex: 1;
        }

        .notification-item-title {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 6px 0;
        }

        .notification-item-description {
            font-size: 14px;
            color: #6b7280;
            margin: 0;
            line-height: 1.4;
        }

        .close-notification-btn {
            background: none;
            border: none;
            color: #6b7280;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .close-notification-btn:hover {
            color: #dc3545;
            transform: scale(1.1);
        }

        .no-notifications {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #9ca3af;
        }

        .no-notifications-icon {
            font-size: 48px;
            margin-bottom: 12px;
        }

        .notification-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .notification-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        @media (max-width: 768px) {
            .notification-panel {
                width: 100%;
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

            <div class="menu-item notification-toggle-sidebar">
                <span>Show notifications</span>
                <label class="toggle-switch">
                    <input type="checkbox" id="notificationToggle" onchange="onNotificationToggleChange(event)">
                    <span class="slider"></span>
                </label>
            </div>

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

        <div class="header-section">

            <button class="notification-btn" onclick="toggleNotificationPanel()" title="Notifications">
                🔔
                <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
            </button>
        </div>

        <!-- Notification Panel -->
        <div class="notification-overlay" id="notificationOverlay" onclick="toggleNotificationPanel()"></div>
        <div class="notification-panel" id="notificationPanel">
            <div class="notification-panel-header">
                <p class="notification-panel-title">Notifications</p>
                <button class="close-panel-btn" onclick="toggleNotificationPanel()">✕</button>
            </div>
            <div class="notification-panel-content" id="notificationPanelContent">
                <!-- Notifications will be loaded here -->
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
        let allNotifications = [];

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

                    // Load notifications
                    allNotifications = user.nfData || [];
                    updateNotificationDisplay();

                    const notificationsEnabled = user.userData.nf_status === '1';
                    const toggle = document.getElementById('notificationToggle');
                    if (toggle) {
                        toggle.checked = notificationsEnabled;
                    }
                    setNotificationVisibility(notificationsEnabled);
                });
        }

        function setNotificationVisibility(show) {
            const notificationButton = document.querySelector('.notification-btn');
            const panel = document.getElementById('notificationPanel');
            const overlay = document.getElementById('notificationOverlay');

            if (notificationButton) {
                notificationButton.style.display = show ? 'inline-flex' : 'none';
            }

            if (!show) {
                panel.classList.remove('active');
                overlay.classList.remove('active');
            }
        }

        function onNotificationToggleChange(event) {
            const showNotifications = event.target.checked;
            setNotificationVisibility(showNotifications);
            saveNotificationStatus(showNotifications);
        }

        function saveNotificationStatus(showNotifications) {
            const formData = new FormData();
            formData.append('nfstatus', showNotifications ? 'true' : 'false');

            fetch(base + 'user/notification-status', {
                method: 'POST',
                credentials: 'include',
                body: formData
            }).catch(console.error);
        }

        function updateNotificationDisplay() {
            const panelContent = document.getElementById("notificationPanelContent");
            const badge = document.getElementById("notificationBadge");

            if (allNotifications.length === 0) {
                panelContent.innerHTML = `
                    <div class="no-notifications">
                        <div class="no-notifications-icon">🔔</div>
                        <p>No notifications</p>
                    </div>
                `;
                badge.style.display = "none";
            } else {
                badge.textContent = allNotifications.length;
                badge.style.display = "flex";

                let html = "";
                allNotifications.forEach((notification, index) => {
                    html += `
                        <div class="notification-item-card">
                            <span class="notification-icon">ℹ️</span>
                            <div class="notification-content">
                                <p class="notification-item-title">${notification.title}</p>
                                <p class="notification-item-description">${notification.description}</p>
                            </div>
                            <button class="close-notification-btn" onclick="removeNotification(${notification.id})" title="Close">✕</button>
                        </div>
                    `;
                });
                panelContent.innerHTML = html;
            }
        }

        function removeNotification(id) {
            // Remove notification from array
            // update the hidden column in database here 

            $.ajax({
                url: "<?= base_url('user/notification/remove') ?>",
                type: "POST",
                data: {
                    notificationId: id,
                },
                dataType: "json",
                success: function(response) {
                    if (response.result) {

                        allNotifications = allNotifications.filter(
                            notification => notification.id != id
                        );
                        updateNotificationDisplay();
                    }
                },
                error: function(error) {
                    console.error("Error removing notification:", error);
                }
            })

        }

        function toggleNotificationPanel() {
            const panel = document.getElementById("notificationPanel");
            const overlay = document.getElementById("notificationOverlay");

            panel.classList.toggle("active");
            overlay.classList.toggle("active");
        }

        function openEditForm() {
            document.getElementById("dashboard").style.display = "none";
            document.getElementById("editBox").style.display = "block";
        }

        function updateProfile() {
            let id = document.getElementById("user_id").innerHTML;
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
    </script>
</body>

</html>
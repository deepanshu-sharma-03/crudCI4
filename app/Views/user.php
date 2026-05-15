<!DOCTYPE html>
<html>

<head>
    <title>User Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #eef5ff;
        }

        .dashboard-card {
            max-width: 500px;
            margin: auto;
            margin-top: 80px;
            border-radius: 10px;
        }

        .profile-title {
            color: #0d6efd;
            font-weight: bold;
        }

        .edit-form {
            width: 60% !important;
            /* margin-top: 2rem !important; */
            padding: 1rem;
            margin: 0 auto;
        }

        .top-bar {
            display: flex;
            justify-content: flex-end;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div style="position: fixed !important; top: 10px !important; left: 10px !important;">

        <button
            onclick="logoutUser()"
            class="btn btn-danger">

            Logout
        </button>
    </div>

    <!-- USER CARD -->
    <div class="card shadow p-4 dashboard-card" id="dashboard">
        <h3 class="text-center profile-title mb-4">
            Welcome <span id="user_name"></span>
        </h3>
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <td id="user_id"></td>
            </tr>

            <tr>
                <th>Email</th>
                <td id="user_email"></td>
            </tr>

            <tr>
                <th>Mobile No.</th>
                <td id="user_mobile_number"></td>
            </tr>
            <tr>
                <td>
                    <a href="user/view-products">View Products</a>
                </td>
            </tr>
        </table>
        <button class="btn btn-warning" onclick="openEditForm()">
            Edit Profile
        </button>
    </div>
    <div id="editBox" style="display:none;" class="mt-4 card shadow p-4 edit-form">
        <h2>Edit Form</h2>
        <input
            type="text"
            id="edit_name"
            class="form-control mb-2"
            placeholder="Name">

        <input
            type="email"
            id="edit_email"
            class="form-control mb-2"
            placeholder="Email">

        <input
            type="text"
            id="edit_mobile"
            class="form-control mb-2"
            placeholder="Mobile Number">
        <input
            type="password"
            id="edit_password"
            class="form-control mb-2"
            placeholder="Password">

        <button
            class="btn btn-success"
            onclick="updateProfile()">
            Save
        </button>
        <button
            class="btn btn-secondary"
            onclick="closeEditForm()">
            Cancel
        </button>

    </div>
    <script>
        let base = "<?= base_url() ?>";
        // SHOW USER DATA
        loadProfile();

        function loadProfile() {

            fetch(
                    base + "get-profile", {
                        credentials: "include"
                    }
                )

                .then(res => res.json())

                .then(user => {
                    console.log(user);
                    document.getElementById(
                        "user_id"
                    ).innerHTML = user.id;

                    document.getElementById(
                        "user_name"
                    ).innerHTML = user.name;

                    document.getElementById(
                        "user_email"
                    ).innerHTML = user.email;

                    document.getElementById(
                            "user_mobile_number"
                        ).innerHTML =
                        user.mobile_number;

                    // EDIT FORM
                    document.getElementById(
                        "edit_name"
                    ).value = user.name;

                    document.getElementById(
                        "edit_email"
                    ).value = user.email;

                    document.getElementById(
                            "edit_mobile"
                        ).value =
                        user.mobile_number;

                    // NEVER SHOW HASHED PASSWORD
                    document.getElementById(
                        "edit_password"
                    ).value = "";
                });
            // edit form 

        }

        function openEditForm() {
            document.getElementById("dashboard").style.display = "none";
            document.getElementById("editBox").style.display = "block";
            // loadProfile()
        }

        function updateProfile() {
            let id = document.getElementById("user_id").value;
            let name = document.getElementById("edit_name").value;
            let email = document.getElementById("edit_email").value;
            let mobile = document.getElementById("edit_mobile").value;
            let password = document.getElementById("edit_password").value;

            fetch("<?= base_url('update-profile') ?>", {

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
            // setTimeout(()=>closeEditForm(),1000);
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
    </script>
</body>

</html>
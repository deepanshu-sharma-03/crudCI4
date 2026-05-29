<!DOCTYPE html>
<html>

<head>
    <title>AJAX CRUD </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            background: #f4f6fb;
            font-family: Arial, sans-serif;
        }

        /* SIDEBAR */

        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #111827;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .profile-box {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-box img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 3px solid #fff;
        }

        .profile-box h5 {
            margin-top: 12px;
        }

        .menu a {
            display: block;
            text-decoration: none;
            color: #d1d5db;
            padding: 14px;
            margin-bottom: 10px;
            border-radius: 10px;
        }

        .menu a:hover {
            background: #2563eb;
            color: white;
        }

        .logout-btn {
            margin-top: auto;
        }

        /* CONTENT */

        .main {
            margin-left: 280px;
            padding: 30px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .searchbar {
            width: 350px;
        }

        .card-box {
            border: none;
            border-radius: 18px;
            overflow: hidden;
        }

        .table th {
            background: #2563eb;
            color: white;
        }

        .page-btn {
            padding: 8px 14px;
            border: none;
            border-radius: 8px;
        }

        .active-page {
            background: #2563eb;
            color: white;
        }
    </style>
</head>

<body>
    <!-- TABLE SECTION -->

    <body>

        <!-- SIDEBAR -->

        <div class="sidebar">

            <div class="profile-box">

                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png">

                <h5>Admin Panel</h5>

            </div>

            <div class="menu">

                <a href="#">🏠 Dashboard</a>

                <a href="#" onclick="loadProducts()">
                    🛒 Products
                </a>

                <a href="#" onclick="openForm()">
                    ➕ Add User
                </a>

                <a href="#">
                    👥 Users
                </a>
                <a href='/notification'>
                    Notifications
                </a>

            </div>

            <button
                onclick="logoutUser()"
                class="btn btn-danger logout-btn">

                Logout

            </button>

        </div>


        <!-- MAIN CONTENT -->

        <div class="main">

            <div class="topbar">

                <h2 class="text-primary">
                    Admin Panel
                </h2>

                <input
                    type="text"
                    id="search"
                    class="form-control searchbar"
                    placeholder="Search users...">

            </div>


            <!-- USER TABLE -->

            <div
                id="tableBox"
                class="card shadow card-box">

                <div class="card-body">
                    <table class="table table-bordered text-center">

                        <thead>

                            <tr>

                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Password</th>
                                <th>Role</th>
                                <th>Action</th>

                            </tr>

                        </thead>

                        <tbody id="userTable">

                        </tbody>

                    </table>

                    <center>

                        <div id="pagination">

                        </div>

                    </center>

                </div>

            </div>

            <!-- FORM -->

            <div
                id="formBox"
                class="card shadow p-4 mt-4"
                style="display:none;">

                <h3 id="formTitle">

                    Add User

                </h3>

                <input type="hidden" id="id">

                <input
                    type="text"
                    id="name"
                    class="form-control mb-2"
                    placeholder="Name">

                <div id="nameError" class="text-danger"></div>

                <input
                    type="email"
                    id="email"
                    class="form-control mb-2"
                    placeholder="Email">

                <div id="emailError" class="text-danger"></div>

                <input
                    type="number"
                    id="mobile_number"
                    class="form-control mb-2"
                    placeholder="Mobile">

                <div id="mobile_numberError" class="text-danger"></div>

                <input
                    type="password"
                    id="password"
                    class="form-control mb-2"
                    placeholder="Password">

                <div id="passwordError" class="text-danger"></div>

                <input
                    type="text"
                    id="role"
                    class="form-control mb-2"
                    placeholder="Role">

                <div id="roleError" class="text-danger"></div>

                <button
                    class="btn btn-success"
                    onclick="saveUser()">

                    Save

                </button>

                <button
                    class="btn btn-secondary"
                    onclick="closeForm()">

                    Cancel

                </button>

            </div>

        </div>

    </body>
    <script>
        let base = "<?= base_url() ?>";
        // Helpers
        const $ = (id) => document.getElementById(id);

        function setError(input, msg) {
            input.classList.add("error");
            $(input.id + "Error").innerText = msg;
        }

        function clearError(input) {
            input.classList.remove("error");
            $(input.id + "Error").innerText = "";
        }
        document.addEventListener("DOMContentLoaded", function() {
            loadUsers(1);
        });
        // Load Users
        document.getElementById("search")
            .addEventListener("keyup", function() {
                loadUsers(1);
            });

        function loadUsers(page = 1) {
            let search = $("search").value;
            fetch(base + "get-users/" + page + "?search=" + search, {
                    credentials: "include"
                })

                .then(res => res.json())
                .then(data => {

                    let rows = "";
                    if (!data.status) {

                        alert(data.message);

                        return;
                    }
                    // if users exist
                    if (data.users.length > 0) {

                        data.users.forEach((u, ind) => {
                            let serialNo = ((page - 1) * 7) + ind + 1;
                            rows += `
                    <tr>
                        <td>${serialNo}</td>
                        <td>${u.name}</td>
                        <td>${u.email}</td>
                        <td>${u.mobile_number}</td>
                        <td>${u.password}</td>
                        <td>${u.role}</td>
                        <td>
                            <button 
                                class="btn btn-warning btn-sm"
                                onclick="editUser(${u.id})"
                            >
                                Edit
                            </button>
                            <button 
                                class="btn btn-danger btn-sm"
                                onclick="deleteUser(${u.id})"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                `;
                        });

                    } else {
                        rows = `
                <tr>
                    <td colspan="7" class="text-danger">
                        No Record Found
                    </td>
                </tr>
            `;
                    }
                    // insert rows
                    $("userTable").innerHTML = rows;

                    // pagination buttons
                    let buttons = "";
                    for (let i = 1; i <= data.totalpages; i++) {
                        buttons += `
                <button 
                    class="page-btn ${page == i ? 'active-page' : ''}"
                    onclick="loadUsers(${i})"
                >
                    ${i}
                </button>
            `;
                    }
                    $("pagination").innerHTML = buttons;
                });
        }

        // Form Controls
        function openForm() {
            $("formTitle").innerText = "Add User";
            $("id").value = "";
            $("name").value = "";
            $("email").value = "";
            $("mobile_number").value = "";
            $("password").value = "";
            $("role").value = "";
            $("tableBox").style.display = "none";
            $("formBox").style.display = "block";
        }

        function closeForm() {
            $("formBox").style.display = "none";
            $("tableBox").style.display = "block";
        }

        // Edit User

        function editUser(id) {
            openForm();
            $("formTitle").innerText = "Edit User";
            fetch(base + "get-user/" + id)
                .then(res => res.json())
                .then(u => {
                    $("id").value = u.id;
                    $("name").value = u.name;
                    $("email").value = u.email;
                    $("mobile_number").value = u.mobile_number;
                    $("password").value = u.password;
                    $("role").value = u.role;
                });
        }

        // Save User (Validation + Submit)

        function saveUser() {
            let id = $("id").value;
            let name = $("name");
            let email = $("email");
            let mobile_number = $("mobile_number");
            let password = $("password");
            let role = $("role");
            let valid = true;

            // clear previous errors
            [name, email, mobile_number, password, role].forEach(clearError);

            // validation
            if (!name.value.trim()) {
                setError(name, "Enter name");
                valid = false;
            }

            if (mobile_number.value.length < 10 || mobile_number.value.length > 10) {
                setError(mobile_number, "Number have 10 digits");
                valid = false;
            }

            if (password.value.length < 6) {
                setError(password, "Min 6 characters");
                valid = false;
            }

            if (!role.value.trim()) {
                setError(role, "Enter role");
                valid = false;
            }

            if (!valid) return;

            let url = id ? "update/" + id : "add-user";

            fetch(base + url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: new URLSearchParams({
                        name: name.value,
                        email: email.value,
                        mobile_number: mobile_number.value,
                        password: password.value,
                        role: role.value
                    })
                })
                .then(res => res.json())
                .then(() => {
                    alert("Saved Successfully");
                    closeForm();
                    loadUsers();
                });

            // remove error on focus (once only)
            [name, email, mobile_number, password, role].forEach(input => {
                input.onfocus = () => clearError(input);
            });
        }

        // Delete User
        function deleteUser(id) {
            if (confirm("Delete this user?")) {
                fetch(base + "delete/" + id)
                    .then(() => loadUsers());
            }
        }

        function logoutUser() {

            window.location.href =
                base + "logout";
        }

        function loadProducts() {
            window.location.href =
                base + "products";
        }
    </script>
</body>

</html>
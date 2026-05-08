<!DOCTYPE html>
<html>
<head>
    <title>AJAX CRUD </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
                body { 
                    background: #eef5ff; 
                }
                .table th { 
                    background: #0d6efd; color: white;
                }
                .error{
                    border: 1px solid red !important;
                }
                .error-text{
                    color: red;
                    font-size: 12px;
                    margin-top: -5px;
                    margin-bottom: 8px;
                }
                .page-btn{
                    padding: 8px 14px;
                    margin: 3px;
                    border: none;
                    background: #e2e6ea;
                    cursor: pointer;
                    border-radius: 5px;
                    font-weight: bold;
                    transition: 0.3s;
                }
                .button{
                    display: inline !important;
                    width: fit-content !important;
                }

                .page-btn:hover{
                    background: #0d6efd;
                    color: white;
                }

                .active-page{
                    background: #0d6efd;
                    color: white;
                }
                .searchbar{
                    width: 40%;
                    border:1px solid black;
                }
    </style>
</head>
<body>
    <!-- TABLE SECTION -->
    <div id="tableBox" class="card p-4 shadow">
        <div style="display:flex; justify-content:space-between; align-items:center; padding:20px;">
            <h3 class="text-primary m-0">
                User List
            </h3>
            <a href="<?= base_url('logout') ?>" class="btn btn-danger">
                Logout
            </a>
        </div>
        <input type="text" id="search" class="form-control searchbar mb-3" placeholder="Search users...">

    <button class="button btn btn-primary mb-3 " onclick="openForm()">+ Add User</button>
     <div id="msg" class="text-danger"></div>
     <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile_No.</th>
                    <th>Password</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="userTable"></tbody>
        </table>
        <center>
            <div id="pagination" class="mt-3"></div>
        </center>
    </div>
    <!-- FORM SECTION -->
    <div id="formBox" class="card p-4 shadow" style="display:none;">
        <h3 id="formTitle">Add User</h3>
        <input type="hidden" id="id">
        <input type="text" autocomplete="off" id="name" class="form-control mb-1" placeholder="Name">
        <div id="nameError" class="error-text"></div>

        <input type="email" id="email" autocomplete="off" class="form-control mb-1" placeholder="Email">
        <div id="emailError" class="error-text"></div>

        <input type="number" id="mobile_number" autocomplete="off" class="form-control mb-1" placeholder="Mobile Number">
        <div id="mobile_numberError" class="error-text"></div>

        <input type="password" autocomplete="off" id="password" class="form-control mb-1" placeholder="Password">
        <div id="passwordError" class="error-text"></div>

        <input type="text" id="role" autocomplete="off" class="form-control mb-1" placeholder="Role">
        <div id="roleError" class="error-text"></div>

    <div id="roleError"></div>
        <button class="btn btn-success" onclick="saveUser()">Save</button>
        <button class="btn btn-secondary" onclick="closeForm()">Cancel</button>
    </div>
</div>
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
document.addEventListener("DOMContentLoaded", function(){
    loadUsers(1);
});
// Load Users
document.getElementById("search")
.addEventListener("keyup", function(){
    loadUsers(1);
});
function loadUsers(page = 1){
    let search = $("search").value;
    fetch(base + "get-users/" + page + "?search=" + search)
    .then(res => res.json())
    .then(data => {
        let rows = "";
        // if users exist
        if(data.users.length > 0){
               data.users.forEach(u => {
                rows += `
                    <tr>
                        <td>${u.id}</td>
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
        for(let i = 1; i <= data.totalpages; i++){
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

function saveUser(){
    let id = $("id").value;
    let name = $("name");
    let email = $("email");
    let mobile_number = $("mobile_number");
    let password = $("password");
    let role = $("role");
    let valid = true;

    // clear previous errors
    [name, email,mobile_number, password, role].forEach(clearError);

    // validation
    if (!name.value.trim()) {
        setError(name, "Enter name");
        valid = false;
    }

    if (!email.value.includes("@gmail.com")) {
        setError(email, "Valid Gmail required");
        valid = false;
    }

    if(mobile_number.value.length < 10 || mobile_number.value.length > 10){
        setError(mobile_number,"Number have 10 digits");
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

    let url = id ? "update/" + id : "store";

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
    [name, email,mobile_number, password, role].forEach(input => {
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
</script>
</body>
</html>
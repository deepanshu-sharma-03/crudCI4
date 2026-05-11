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
        .edit-form{
            width:60% !important;
            /* margin-top: 2rem !important; */
            padding: 1rem;
            margin: 0 auto;
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
<div class="card shadow p-4 dashboard-card" id="dashboard">
    <h3 class="text-center profile-title mb-4">
        Welcome <?= session()->get('name') ?>
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
            <th>Email</th>
            <td><?= session()->get('email') ?></td>
        </tr>
        <tr>
            <th>Password</th>
            <td><?=  session()->get('password') ?></td>
        </tr>
        <tr>
            <th>Mobile No.</th>
            <td><?= session()->get('mobile_number') ?></td>
        </tr>
       
    </table>
    <button class="btn btn-warning" onclick="openEditForm()" >
    Edit Profile
</button>
</div>
<div id="editBox" style="display:none;" class="mt-4 card shadow p-4 edit-form">
    <h2>Edit Form</h2>
    <input 
        type="text"
        id="edit_name"
        class="form-control mb-2"
        placeholder="Name"
    >

    <input 
        type="email"
        id="edit_email"
        class="form-control mb-2"
        placeholder="Email"
    >

    <input 
        type="text"
        id="edit_mobile"
        class="form-control mb-2"
        placeholder="Mobile Number"
    >
    <input 
    type="password"
    id="edit_password"
    class="form-control mb-2"
    placeholder="Password"
    >

    <button 
        class="btn btn-success"
        onclick="updateProfile()"
    >
        Save
    </button>
    <button 
    class="btn btn-secondary"
    onclick="closeEditForm()"
    >
        Cancel
    </button>

</div>
<script>
    function openEditForm(){
    document.getElementById("dashboard").style.display = "none";
    document.getElementById("editBox").style.display = "block";
    
    fetch("<?= base_url('get-profile') ?>")
    .then(res => res.json())
    .then(user => {
        document.getElementById("edit_name").value = user.name;
        document.getElementById("edit_email").value = user.email;
        document.getElementById("edit_mobile").value = user.mobile_number;
        document.getElementById("edit_password").value = user.password;
    });
}
function updateProfile(){

    let name = document.getElementById("edit_name").value;
    let email = document.getElementById("edit_email").value;
    let mobile = document.getElementById("edit_mobile").value;
    let password = document.getElementById("edit_password").value;

    fetch("<?= base_url('update-profile') ?>", {

        method:"POST",
        headers:{
            "Content-Type":"application/x-www-form-urlencoded"
        },
        body:new URLSearchParams({
            name:name,
            email:email,
            mobile_number:mobile,
            password:password,
        })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        location.reload();
    });
    // setTimeout(()=>closeEditForm(),1000);
}

// close Edit Form
    function closeEditForm(){
        document.getElementById('editBox').style.display = "none";
        document.getElementById('dashboard').style.display = "block";
    }

</script>
</body>
</html>
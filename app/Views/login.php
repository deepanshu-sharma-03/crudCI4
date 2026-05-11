<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>

        body{
            background:#eef5ff;
        }

        .login-box{
            width:400px;
            margin:80px auto;
            background:white;
            padding:30px;
            border-radius:10px;
        }

        #otpBox{
            display:none;
        }
        .edit-form{
    width:400px;
    margin:50px auto;
    display:none;
}

        .msg{
            margin-top:10px;
            font-weight:bold;
        }
        #dashboardBox{
    width:500px;
    margin:50px auto;
}
    </style>
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

        <!-- <form method="post" autocomplete="off" action="<?= base_url('loginUser') ?>"> -->
            <div>
            <input type="email"
                   name="email"
                   id="email"
                   class="form-control mb-3"
                   placeholder="Email"
                   required >
            <input type="password"
                   name="password"
                   id="password"
                   class="form-control mb-3"
                   placeholder="Password"
                   required>
            <button 
    type="button"
    class="btn btn-primary w-100"
    onclick="sendOtp()"
>
    Send OTP
</button>
        <div id="otpBox" class="mt-4">

        <input 
            type="text"
            id="otp"
            class="form-control mb-3"
            placeholder="Enter OTP"
        >

        <button 
            class="btn btn-success w-100"
            onclick="verifyOtp()"
        >
            Verify OTP
        </button>

    </div>
        <div id="message" class="msg text-center"></div>

            <!-- <button class="btn btn-primary w-100">
                Login
            </button> -->
</div>
        <a href="<?= base_url('google-login') ?>"
            class="btn btn-primary w-100 mt-2">
            Sign Up With Google
        </a>

        <a href="<?= base_url('register') ?>" class="mt-3 text-center">
            Create Account
        </a>
    </div>
</div>
<script>

    let base = "<?= base_url() ?>/";

    // Send OTP
    function sendOtp(){
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;
    fetch(base + "send-otp", {
        method:"POST",
        headers:{
            "Content-Type":"application/x-www-form-urlencoded"
        },
        body:new URLSearchParams({
            email:email,
            password:password
        })
    })
    .then(res => res.json())
    .then(data => {
        let msg = document.getElementById("message");
        if(data.status){
            // show otp input
            document.getElementById("otpBox").style.display = "block";
            msg.innerHTML = "OTP Sent Successfully";
            msg.style.color = "green";
        }else{
             msg.innerHTML = data.message;
            msg.style.color = "red";
        }
    });
}

// Verify OTP
function verifyOtp(){
    let otp = document.getElementById("otp").value;
    fetch(base + "verify-otp", {
        method:"POST",
        headers:{
            "Content-Type":"application/x-www-form-urlencoded"
        },
        body:new URLSearchParams({
            otp:otp
        })
    })
    .then(res => res.json())
    .then(data => {
        let msg = document.getElementById("message");
        if(data.status){
            msg.innerHTML = "OTP Verified Successfully";
            msg.style.color = "green";
            setTimeout(() => {
                window.location.href = data.redirect;
            },1000);
        }else{
            msg.innerHTML = data.message;
            msg.style.color = "red";
        }
    });
}
</script>
</body>
</html>
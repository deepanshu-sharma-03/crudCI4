<!DOCTYPE html>
<html>
<head>
    <title>Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <style>
    .error{
        border:1px solid red !important;
        }
    .error-text{
            color: red;
            font-size: 12px;
            margin-top: -10px;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="card p-4 shadow mx-auto" style="max-width:400px;">
        <h3 class="text-center mb-4">Register</h3>
     <form method="post" id="registerForm" autocomplete="off" action="<?= base_url('registerUser') ?>">
                <input type="text"
            id="name"
            name="name"
            class="form-control mb-3"
            placeholder="Name">
            <div id="nameError" class="error-text"></div>
            <input type="email"
                id="email"
                name="email"
                class="form-control mb-3"
                placeholder="Email">
            <div id="emailError" class="error-text"></div>
            <input type="number"
                id="mobile_number"
                name="mobile_number"
                class="form-control mb-3"
                placeholder="mobile_number">
            <div id="mobile_numberError" class="error-text"></div>
            <input type="password"
                id="password"
                name="password"
                class="form-control mb-3"
                placeholder="Password">
            <div id="passwordError" class="error-text"></div>
                <button class="btn btn-success w-100">
                            Register
                </button>
    </form>

        <a href="<?= base_url('login') ?>" class="mt-3 text-center">
            Already have account?
        </a>

    </div>

</div>
<script>
    document.getElementById("registerForm")
.addEventListener("submit", function(e){

        let name = document.querySelector("#name");
        let email = document.querySelector("#email");
        let mobile_number = document.querySelector("#mobile_number");
        let password = document.querySelector("#password");
        let isValid = true;

    // STOP FORM
    e.preventDefault();

    // RESET
    name.classList.remove("error");
    email.classList.remove("error");
    mobile_number.classList.remove('error');
    password.classList.remove("error");

    document.getElementById("nameError").innerText = "";
    document.getElementById("emailError").innerText = "";
    document.getElementById("mobile_numberError").innerText = "";
    document.getElementById("passwordError").innerText = "";

    // NAME
    if(name.value.trim() == ""){
        name.classList.add("error");
        document.getElementById("nameError").innerText =
        "Enter name";
        isValid = false;
    }

    // EMAIL
    if(email.value.trim() == ""){
        email.classList.add("error");
        document.getElementById("emailError").innerText =
        "Enter email";
        isValid = false;

    }else if(!email.value.includes("@gmail.com")){
        email.classList.add("error");
        document.getElementById("emailError").innerText =
        "Enter valid gmail";
        isValid = false;
    }
    // Mobile_No
    if(mobile_number.value.trim().length > 10 || mobile_number.value.trim().length < 10){
        mobile_number.classList.add("error");
        document.getElementById("mobile_numberError").innerText = "Number should have 10 digits";
        isValid = false;
    }

    // PASSWORD
    if(password.value.trim() == ""){
        password.classList.add("error");
        document.getElementById("passwordError").innerText =
        "Enter password";
        isValid = false;

    }else if(password.value.length < 6){
        password.classList.add("error");
        document.getElementById("passwordError").innerText =
        "Minimum 6 characters";
        isValid = false;
    }

    // IF VALID SUBMIT FORM
    if(isValid){
        this.submit();
    }
});
</script>
</body>
</html>
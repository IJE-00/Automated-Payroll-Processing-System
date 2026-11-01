<?php
include "db.php";

$msg ="";

if(isset($_POST['register'])){

    $admin_name = $_POST['admin_name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "
        SELECT * FROM admin_acc 
        WHERE admin_name='$admin_name'
    ");

    if(mysqli_num_rows($check) > 0){

        $msg = "Account already exists!";

    } else {

        mysqli_query($conn, "
            INSERT INTO admin_acc (admin_name, password, status) 
            VALUES ('$admin_name', '$password', 'DEACTIVATE')
        ");

        $msg = "Contact other admin for approval.";
    }
}
?>

<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" 
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
<form method="POST">

    <div id="logo-box">
        <img id="logo" src="../logo.png">
    </div>

    <div id="regis-container">
        <h1>ADMIN REGISTER</h1>

        <div id="input-box">
            <i class="fa-solid fa-user"></i>
            <label>ADMIN NAME:</label>
            <input type="text" name="admin_name" autocomplete="off" required>
        </div>

        <div id="input-box">
            <i class="fa-solid fa-key"></i>
            <label>PASSWORD:</label>
            <input type="text" name="password" autocomplete="off" required>
        </div>

        <div id="buttons">
            <input id="bot" type="submit" name="register" value="REGISTER">
            <button id="bot" type="button" onclick="location.href='admin_login.php'">BACK</button>
        </div>

        <div id="message"><?php echo $msg; ?></div>
    </div>

</form>

</body>
</html>

<style>
    *{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
    background: url('../background.jpg') no-repeat center center/cover;
}

/* glass card */
#regis-container{
    position:relative;
    width:500px;
    padding:40px 25px;
    z-index: 10;

    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;

    border-radius:20px;

    background: rgba(23, 22, 22, 0.35);
    backdrop-filter: blur(12px);

    border:1px solid rgba(255,255,255,0.3);
    box-shadow:0 0 30px rgba(255,255,255,0.2);
}

/* logo */
#logo-box{
    position: absolute;
    top: 20px;
    left: 20px;
    z-index: 20;
}

#logo{
    width:200px;
}

/* title */
h1{
    margin-top: 20px;
    margin-bottom: 25px;
    font-size: 40px;
    font-weight: bold;
    color: #edeeef;
    text-shadow: 0 0 10px #999f9f, 0 0 20px #f5fafa;
}

/* inputs */
#input-box{
    width:300px;
    text-align:left;
    margin-bottom:15px;
}

i{
    color: cyan;
}

label{
    font-family: Copperplate, "Copperplate Gothic Light", fantasy;
    font-size:14px;
    font-weight:bold;
    color:white;
}

input[type="text"]{
    width:100%;
    padding:12px;
    margin-top:5px;

    border:none;
    border-radius:10px;

    outline:none;
    background:rgba(255,255,255,0.2);
    color:#fff;

    transition:0.3s;
    box-sizing:border-box;
}

input:hover{
    background:rgba(255,255,255,0.3);
    box-shadow:0 0 10px rgba(0,150,255,0.5);
}

/* buttons */
#buttons{
    display:flex;
    gap:20px;
    margin-top:10px;
}

#bot{
    margin-bottom: 20px;
    height:50px;
    width:120px;
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    font-weight:bold;
    border-radius:10px;
    border:none;
    box-shadow: 0 0 20px #eeeeee;

    background-image: linear-gradient(to right, #629dfa 0%, #c2e9fb 51%, #629dfa 100%);
    background-size:200% auto;

    cursor:pointer;
    transition:0.3s;
}

#bot:hover{
    background-position:right center;
    box-shadow: 0 0 20px #0855b8;
    transform:translateY(-5px);
}

/* message */
#message{
    margin-top:10px;
    color:yellow;
    font-weight:bold;
}
</style>
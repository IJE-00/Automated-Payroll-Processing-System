<?php
session_start(); // Start session to access login data
include "db.php";

// Redirect user if not logged in (security check)
if(!isset($_SESSION['fullname'])){
    header("Location: employees_login.php");
    exit();
}

// Get logged-in user from session
$fullname = $_SESSION['fullname'];

$msg = "WELCOME <br>" . $fullname;

// Attendance button
if(isset($_POST['attendance'])){
    header("Location: attendance.php");
    exit();
}

// Payroll button
if(isset($_POST['payroll'])){
    header("Location: payroll.php");
    exit();
}

// History button
if(isset($_POST['history'])){
    header("Location: history.php");
    exit();
}

// Logout button
if(isset($_POST['logout'])){
    session_destroy(); // destroy session
    header("Location: employees_login.php");
    exit();
}
?>

<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" 
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<form method="POST">
    <div id="logo-box">
        <img id="logo" src="../logo.png">
    </div>
    <div id="container">
        <a><?php echo $msg;?></a>

        <div id="buttons">
            <button id="bots" name="attendance"><i class="fa-solid fa-business-time"></i>Attendance</button>
            <button id="bots" name="payroll"><i class="fa-brands fa-paypal"></i>Payroll</button>
            <button id="bots" name="history">History of Payroll</button>
            <button id="bots" type="button" onclick="location.href='employees_login.php'"><i class="fa-solid fa-right-from-bracket"></i>Logout</button>
        </div>
    </div>
</form>
</html>

<style>
*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body{
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
    background: url('../background.jpg') no-repeat center center/cover;
}

/* dark overlay */
body::before{
    content:"";
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;

    background: rgba(0, 0, 0, 0.4);
    z-index:1;
}

#container{
    position:relative;
    z-index:10;

    display:flex;
    flex-direction:row;
    align-items:center;
    gap: 100px;
}

#logo-box{
    position: absolute;
    top: 20px;
    left: 20px;

    display: flex;
    align-items: center;
    gap: 10px;

    z-index: 20; 
}

#logo{
    width: 200px; 
}


i{
    margin-right: 6px;
}

a{
    display: flex;
    justify-content: center;
    align-items: center;
    
    font-size: 75px; 
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    font-weight: bold;
    background: linear-gradient(to right, #1865f3, #82a9ed);
    background-clip: text;
    -webkit-text-fill-color: transparent;
    
    filter: drop-shadow(0 4px 4px rgba(0, 0, 0, 0.5));
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

a:hover {
    transform: scale(1.05);
    filter: drop-shadow(0 0 15px rgba(221, 234, 235, 0.4));
}

#buttons{
    display: flex;
    gap: 20px;
    background:rgba(255,255,255,0.15);
    backdrop-filter:blur(10px);
    border-radius:20px;
    border:1px solid rgba(255,255,255,0.3);

    height: 400px;
    width: 400px;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

#bots{
    display: flex;
    align-items: center;
    justify-content: center;
    height:70px;
    width:180px;
    font-size: 20px;
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

#bots:hover{
    background-position:right center;
    box-shadow: 0 0 20px #0855b8;
    transform:translateY(-5px);
}

    
</style>

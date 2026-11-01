<?php
include "db.php";
?>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" 
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<form method="POST">
    <div id="header">
        <img id="logo" src="../logo.png">
    </div>
    
    <button type="button" onclick="location.href ='admin_acc.php'"><i class="fa-solid fa-users" style="margin-right:10px;"></i>ADMINS ACCOUNT</button><br>
    <button type="button" onclick="location.href ='admin_emp_acc.php'"><i class="fa-solid fa-users" style="margin-right:10px;"></i>EMPLOYEES ACCOUNT</button><br>
    <button type="button" onclick="location.href ='admin_emp_pay.php'"><i class="fa-solid fa-file-pen" style="margin-right:10px;"></i>EDIT PAYROLL</button><BR>
    <button type="button" onclick="location.href ='admin_emp_history.php'"><i class="fa-solid fa-clipboard-user" style="margin-right:10px;"></i>PAYOUT RECORDS</button><BR>
    <button type="button" onclick="location.href='admin_login.php'"><i class="fa-solid fa-right-from-bracket" style="margin-right:10px;"></i> LOGOUT</button>
</form>

<style>
 *{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body{
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow:hidden;
    background: url('../background.jpg') no-repeat center center/cover;
}



/* CENTER PANEL */
form{
    position: relative;
    z-index: 10;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    height: 550px;
    width: 600px;

    border-radius: 20px;
    background: rgba(23, 22, 22, 0.35);
    backdrop-filter: blur(10px);

    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 0 30px rgba(255,255,255,0.2);
}

/* LOGO */
#logo{
    width: 200px;
    margin-bottom: 10px;
}

/* BUTTON CONTAINER */
form button{
    margin: 10px;
}

/* BUTTON STYLE (same as homepage) */
button{
    padding:1rem;
    height: 100px;
    width: 280px;

    font-family: Copperplate, "Copperplate Gothic Light", fantasy;
    font-weight: bold;
    font-size: 17px;

    border-radius: 10px;
    border: none;

    background-image: linear-gradient(to right, #629dfa 0%, #c2e9fb 51%, #629dfa 100%);
    background-size: 200% auto;

    cursor: pointer;
    transition: 0.3s;

    box-shadow: 0 0 20px #eeeeee;
}

button:hover{
    background-position: right center;
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 10px 20px #0372bd;
}

</style>
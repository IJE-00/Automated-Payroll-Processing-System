<?php
include "db.php";
?>

<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Black+Ops+One&display=swap" rel="stylesheet">
<title>Payroll System</title>

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

    background: url('../background.jpg') center center/cover;
}


/* CONTAINER */
#container{
    position:relative;
    width:600px;
    padding:40px 25px;
    z-index: 1;

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

/* LOGO */
#logo{
    width:100%;
    max-width:320px;
    height:auto;
}

/* TITLE */
h1{
    margin-top:20px;
    font-size:24px;
    text-align:center;

    font-family: "Black Ops One", system-ui;
    font-weight: 400;

    font-weight: bold;
    color: #edeeef;
    text-shadow: 0 0 10px #31a5a9, 0 0 20px #00f7ff;
}

/* BUTTON WRAPPER */
#bot{
    display:flex;
    gap:20px;
    margin-top:30px;
    width:100%;
}

/* BUTTONS */
button{
    flex:1;
    padding:15px;

    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    font-weight:bold;
    font-size:18px;

    border-radius:10px;
    border:none;

    background: linear-gradient(to right, #629dfa 0%, #c2e9fb 51%, #629dfa 100%);
    background-size:200% auto;

    cursor:pointer;
    transition:0.3s;

    box-shadow:0 0 15px #eeeeee;
}

button:hover{
    background-position:right center;
    transform:translateY(-5px) scale(1.05);
    box-shadow:0 10px 20px #0372bd;
}

@media (max-width: 500px){
    #bot{
        flex-direction:column;
    }

    button{
        width:100%;
    }
}


</style>
</head>

<body>

<div id="container">
    <img id="logo" src="../logo.png">
    <h1>Automated Payroll Processing System</h1>

    <div id="bot">
        <button onclick="location.href='../ADMIN/admin_login.php'">ADMIN</button>
        <button onclick="location.href='../EMPLOYEES/employees_login.php'">EMPLOYEES</button>
    </div>
</div>

</body>
</html>
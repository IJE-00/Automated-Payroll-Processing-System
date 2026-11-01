<?php
session_start(); 
include "db.php";

if(!isset($_SESSION['fullname'])){
    header("Location: employees_login.php");
    exit();
}

$fullname = $_SESSION['fullname'];

$msg = $fullname;
$message = "";

/* ================= TIMEZONE ================= */
// Time settings
date_default_timezone_set('Asia/Manila');
$currentMonth = date("m");
$currentYear = date("Y");

// PRESENT BUTTON
if(isset($_POST['present'])){

    $today = date("Y-m-d");

    // Check if already marked present today
    $check = mysqli_query($conn,"
        SELECT * FROM attendance
        WHERE fullname='$fullname'
        AND status='Present'
        AND DATE(date)='$today'
    ");

    // ONCE A DAY
    if(mysqli_num_rows($check) == 0){

        mysqli_query($conn,"
            INSERT INTO attendance (fullname, status, date)
            VALUES ('$fullname','Present', NOW())
        ");

    } else {
        $message = "You have already marked yourself as present today.";
    }
}

// BACK BUTTON
if(isset($_POST['back'])){
    header("Location: employees_view.php");
    exit();
}

// TABLE (current month records)
$table = mysqli_query($conn,"
    SELECT * FROM attendance 
    WHERE fullname='$fullname'
    AND MONTH(date)= '$currentMonth' 
    AND YEAR(date)= '$currentYear'
    ORDER BY date DESC
");

// COUNT PRESENT THIS MONTH
$present = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM attendance 
    WHERE fullname='$fullname' 
    AND status='Present'
    AND MONTH(date)= '$currentMonth' 
    AND YEAR(date)= '$currentYear'
");        

$count = mysqli_fetch_assoc($present);
$total = $count['total'] ?? 0;

// cycle logic 15 days
$cycleCount = (($total - 1) % 15) + 1;
?>

<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" 
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<form method="POST">
    <link href="https://fonts.googleapis.com/css2?family=Zen+Dots&display=swap" rel="stylesheet">
    <div id="logo-box">
        <img id="logo" src="../logo.png">
    </div>

<div id="container">
    <div id="box">
        <h1><?php echo $msg;?></h1>
        <div id="buttons">
            <button id="bot" type="submit" name="present">Present</button>
            <button id="bot" name="back">BACK</button>
        </div>
    </div>

    <div id="box"> 
        <label><?php echo $message; ?></label>
        <div id="contable">
            <div class="table-scroll">
                <table>
                    <tr>
                        <th>TOTAL PRESENT (THIS MONTH)</th>
                        <td style="text-align:center;">
                            <?php echo $cycleCount; ?>
                        </td>
                    </tr>

                    <tr>
                        <th>FULLNAME</th>
                        <th>DATE AND TIME</th>
                    </tr>

                    <?php while($rows = mysqli_fetch_assoc($table)) { ?>
                        <tr style="width:100px;">
                            <td style="text-align:center;"><?php echo $rows['fullname']; ?></td>
                            <td style="text-align:center;"><?php echo date("M d, Y h:i A", strtotime($rows['date'])); ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
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
    z-index:-1;
}

#container{
    display: flex;
    gap: 100px;
}

#box{
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center; 
    gap: 20px;
    text-align: center;
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

label{
    font-size:20px;
    font-weight:bold;
    text-align:center;
    color:white;
}


h1{
    display: flex;
    font-size: 60px;
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    background: linear-gradient(to right, #1865f3, #82a9ed);
    background-clip: text;
    -webkit-text-fill-color: transparent;

    filter: drop-shadow(0 4px 4px rgba(0, 0, 0, 0.5));
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

h1:hover{
    transform: scale(1.05);
    filter: drop-shadow(0 0 15px rgba(221, 234, 235, 0.4));
}

.table-scroll{
    max-height: 320px;
    overflow-y: auto;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    background: white;
}


table{
    width: 600px;
    border-collapse: collapse;
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    font-size: 14px;
    color: #333;
    background: white;
    border-radius: 10px;
    overflow: hidden;
}

/* header */
th{
    background: linear-gradient(to right, #111cee, #5964f4ab);
    color: white;
    padding: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 12px;
    border: solid 1px black;
}

/* cells */
td{
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #eee;
    font-weight: bold;
}

/* zebra rows */
tr:nth-child(even){
    background-color: #f7f9ff;
}

/* hover effect */
tr:hover{
    background-color: #eaf0ff;
    transition: 0.2s;
}
        
#buttons{
    display:flex;
    gap:20px;
    margin-top:10px;
}

#bot{
    margin-bottom: 20px;
    height:70px;
    width:130px;
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


</style>

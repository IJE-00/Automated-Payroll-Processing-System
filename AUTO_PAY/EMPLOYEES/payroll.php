<?php
session_start();
include "db.php";

//timezone
date_default_timezone_set('Asia/Manila');

if(!isset($_SESSION['fullname'])){
    header("Location: employees_login.php");
    exit();
}

$fullname = $_SESSION['fullname'];

$msg = "Salary of " . $fullname . " in a Month";

/* ================= DEDUCTION MODE ================= */
if (!isset($_SESSION['deduction_mode'])) {
    $_SESSION['deduction_mode'] = "with";
}

if (isset($_POST['with_deduction'])) {
    $_SESSION['deduction_mode'] = "with";
}

if (isset($_POST['no_deduction'])) {
    $_SESSION['deduction_mode'] = "none";
}

$deduction_mode = $_SESSION['deduction_mode'];

/* ================= EMPLOYEE DATA ================= */
$default = mysqli_query($conn, "
    SELECT * FROM emp_grosspay 
    WHERE fullname='$fullname' 
    LIMIT 1
");

$def = mysqli_fetch_assoc($default);

$default_payment = $def['payment'] ?? 0;
$default_sss = $def['sss'] ?? 0;
$default_philhealth = $def['philhealth'] ?? 0;
$default_pagibig = $def['pagibig'] ?? 0;

/* ================= ATTENDANCE PAYROLL ================= */
$currentMonth = date("m");
$currentYear = date("Y");

$pay = mysqli_query($conn, "
    SELECT 
        fullname,
        MONTH(date) as month,
        YEAR(date) as year,
        COUNT(*) as days
    FROM attendance
    WHERE fullname='$fullname'
    AND status='Present'
    AND MONTH(date)=MONTH(CURRENT_DATE())
    AND YEAR(date)=YEAR(CURRENT_DATE())
    GROUP BY fullname, MONTH(date), YEAR(date)
");

/* ================= SAVE RECEIPT ================= */
if (isset($_POST['receipt'])) {

    $payment = $_POST['payment'];
    $sss = $_POST['sss'];
    $philhealth = $_POST['philhealth'];
    $pagibig = $_POST['pagibig'];
    $days = $_POST['days'];
    $total_days_pay = $_POST['total_days_pay'];
    $total_payment = $_POST['total_payment'];
    $month = $_POST['month'];
    $year = $_POST['year'];

    // RESET ATTENDANCE AFTER PAYROLL
    mysqli_query($conn, "
        DELETE FROM attendance
        WHERE fullname='$fullname'
        AND MONTH(date)='$month'
        AND YEAR(date)='$year'
    ");

    // SAVE HISTORY
    mysqli_query($conn, "
        INSERT INTO history 
        (fullname,payment,sss,philhealth,pagibig,
        days,total_days_pay,total_payment,
        month,year,receipt_status)
        VALUES
        ('$fullname','$payment','$sss','$philhealth','$pagibig',
        '$days','$total_days_pay','$total_payment',
        '$month','$year','Received')
    ");

    echo "<script>
        alert('Receipt saved successfully');
        window.location.href='history.php';
    </script>";
    exit();
}

/* ================= BACK ================= */
if (isset($_POST['back'])) {
    header("Location: employees_view.php");
    exit();
}
?>


<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" 
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<div id="contable">
    <div id="logo-box">
        <img id="logo" src="../logo.png">
    </div>
    <h1><?php echo $msg; ?></h1>

    <form method="POST" id="deduct">
        <button id="bot" name="with_deduction">WITH DEDUCTION</button>
        <button id="bot" name="no_deduction">NO DEDUCTION</button>
    </form>

    <h3 style="margin:20px; text-align:center; color:white;">
    Mode: <?php echo ($deduction_mode == "none") ? "NO DEDUCTION" : " WITH DEDUCTION"; ?>
    </h3>

    <table>

        <tr>
            <th>PAYMENT</th>
            <th>SSS</th>
            <th>PHILHEALTH</th>
            <th>PAG-IBIG</th>
            <th>MONTH - YEAR</th>
            <th>DAYS OF WORK</th>
            <th>TOTAL DAYS PAYMENT</th>
            <th>NET SALARY</th>
            <th>STATUS</th>
        </tr>

        <?php
        if($pay && mysqli_num_rows($pay)>0){

            while($rows=mysqli_fetch_assoc($pay)){
            $daily_pay = $default_payment;

                /*DEDUCTION */
                if($deduction_mode == "none"){
                    $sss = 0;
                    $philhealth = 0;
                    $pagibig = 0;
                } else {
                    $sss = $default_sss;
                    $philhealth = $default_philhealth;
                    $pagibig = $default_pagibig;
                }

                $days=$rows['days'];
                $cycleDays = (($days - 1) % 15) + 1;
                $total_days_pay=$daily_pay*$days;
                $total=$total_days_pay-($sss+$philhealth+$pagibig);


                $monthNumber=$rows['month'];
                $monthName=date("F",mktime(0,0,0,$monthNumber,10));
        ?>

        <tr>
            <td align="center"><?php echo $daily_pay; ?></td>
            <td align="center"><?php echo $sss; ?></td>
            <td align="center"><?php echo $philhealth; ?></td>
            <td align="center"><?php echo $pagibig; ?></td>

            <td align="center">
            <?php echo $monthNumber." - ".$rows['year']; ?>
            </td>

            <td align="center"><?php echo $cycleDays; ?></td>
            <td align="center"><?php echo $total_days_pay; ?></td>
            <td align="center"><?php echo $total; ?></td>

            <td align="center">

            <form method="POST">

                <input type="hidden" name="fullname" value="<?php echo $rows['fullname']; ?>">
                <input type="hidden" name="payment" value="<?php echo $daily_pay; ?>">
                <input type="hidden" name="sss" value="<?php echo $sss; ?>">
                <input type="hidden" name="philhealth" value="<?php echo $philhealth; ?>">
                <input type="hidden" name="pagibig" value="<?php echo $pagibig; ?>">
                <input type="hidden" name="days" value="<?php echo $days; ?>">
                <input type="hidden" name="total_days_pay" value="<?php echo $total_days_pay; ?>">
                <input type="hidden" name="total_payment" value="<?php echo $total; ?>">
                <input type="hidden" name="month" value="<?php echo $monthNumber; ?>">
                <input type="hidden" name="year" value="<?php echo $rows['year']; ?>">
                <input type="hidden" name="deduction_mode" value="<?php echo $deduction_mode; ?>">

                <button type="submit" name="receipt" id="receive">RECEIVE</button>


            </form>

            </td>

        </tr>
        <?php
        } 
        }else{
            echo "<tr><td colspan='10' style='color:red;text-align:center;'>No payroll data found</td></tr>";
        }
        ?>

    </table>
    <br>

    <div id="back_bot">
        <form method="POST">
            <button name="back" id="bot">BACK</button>
        </form>
    </div>

</div>

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

/* TITLE */
h1{
    text-align: center;
    font-size:45px;
    color: #edeeef;
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    text-shadow: 0 0 10px #31a5a9, 0 0 20px #00f7ff;
    margin-bottom: 20px;
}

/* CONTAINER */
#contable{
    max-width: 95%;
    margin: auto;
}


/* SCROLL TABLE */
.table-scroll{
    max-height: 450px;
    overflow-y: auto;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    background: white;
}

/* TABLE */
table{
    width: 100%;
    border-collapse: collapse;
}

/* HEADER */
th{
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    background: linear-gradient(to right, #4e54c8, #8f94fb);
    color: white;
    padding: 14px;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 1px;
    z-index: 2;
}

/* CELLS */
td{
    padding: 12px;
    font-size: 15px;
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    text-align: center;
    border: 2px solid #0d0d15;
    font-weight: bold;

}

td:nth-child(8){
    color: #329038;
    letter-spacing: 1px;
}

/* ROW EFFECTS */
tr:nth-child(even){
    background: #f7f9ff;
}

tr:hover{
    background: #eaf0ff;
    transition: 0.2s;
}

#logo-box{
    position: absolute;
    top: 20px;
    left: 20px;

    display: flex;
    align-items: center;
    gap: 10px;

    z-index: 20; /* above background */
}

#logo{
    width: 200px; /* adjust size if needed */
}

/* BUTTON */

#back_bot{
    display:flex;
    justify-content:center;
    margin-top:px;
}
#bot{
    padding: 10px 20px;
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    font-weight: bold;
    font-size: 20px;

    border-radius: 10px;
    border: none;

    background-image: linear-gradient(to right, #629dfa 0%, #c2e9fb 51%, #629dfa 100%);
    background-size: 200% auto;

    cursor: pointer;
    transition: 0.3s;

    box-shadow: 0 0 20px #eeeeee;
}

#bot:hover{
    background-position: right center;
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 10px 20px #0372bd;
}

#deduct{
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
    gap: 20px;
}

#receive{
    padding: 10px 20px;
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    font-weight: bold;
    font-size: 20px;

    border-radius: 10px;
    border: none;

    background: linear-gradient(to right, #00c853, #b2ff59);
    background-size: 200% auto;

    cursor: pointer;
    transition: 0.3s;

    box-shadow: 0 0 20px #eeeeee;
}

#receive:hover{
    background-position: right center;
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 10px 20px #018715;
}

</style>
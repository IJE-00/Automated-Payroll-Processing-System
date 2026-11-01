<?php
include "db.php";

/* ================= GET BASE DATA ================= */
$current = mysqli_query($conn, "SELECT * FROM emp_grosspay LIMIT 1");
$data = mysqli_fetch_assoc($current);

$payment_val = $data['payment'] ?? '';
$sss_val = $data['sss'] ?? '';
$pagibig_val = $data['pagibig'] ?? '';
$philhealth_val = $data['philhealth'] ?? '';

/* ================= ENSURE TABLE HAS DATA ================= */
$count = mysqli_query($conn, "SELECT COUNT(*) as total FROM emp_grosspay");
$row = mysqli_fetch_assoc($count);

if ($row['total'] == 0) {
    mysqli_query($conn, "
        INSERT INTO emp_grosspay (payment, sss, philhealth, pagibig)
        VALUES ('$payment_val', '$sss_val', '$philhealth_val', '$pagibig_val')
    ");
}

/* ================= SEARCH ================= */
if (isset($_POST['search'])) {

    $fullname = $_POST['fullname'];

    $result = mysqli_query($conn, "
        SELECT * FROM emp_grosspay
        WHERE fullname LIKE '%$fullname%'
        ORDER BY fullname ASC
    ");

} else {

    $result = mysqli_query($conn, "
        SELECT * FROM emp_grosspay
        ORDER BY fullname ASC
    ");
}

/* ================= UPDATE ALL DEDUCTIONS ================= */
if (isset($_POST['update_all'])) {

    $sss = $_POST['sss'];
    $pagibig = $_POST['pagibig'];
    $philhealth = $_POST['philhealth'];

    mysqli_query($conn, "
        UPDATE emp_grosspay 
        SET sss='$sss',
            pagibig='$pagibig',
            philhealth='$philhealth'
    ");

    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

/* ================= UPDATE PAYMENT (PER PERSON) ================= */
if (isset($_POST['update_payment'])) {

    $id = $_POST['id'];
    $payment = $_POST['payment'];

    mysqli_query($conn, "
        UPDATE emp_grosspay 
        SET payment='$payment'
        WHERE id='$id'
    ");

    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

/* ================= INDIVIDUAL DEDUCTIONS ================= */
if (isset($_POST['update_sss'])) {
    mysqli_query($conn, "UPDATE emp_grosspay SET sss='{$_POST['sss']}'");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

if (isset($_POST['update_pagibig'])) {
    mysqli_query($conn, "UPDATE emp_grosspay SET pagibig='{$_POST['pagibig']}'");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

if (isset($_POST['update_philhealth'])) {
    mysqli_query($conn, "UPDATE emp_grosspay SET philhealth='{$_POST['philhealth']}'");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

/* ================= TOTAL EMPLOYEES ================= */
$total = mysqli_query($conn, "SELECT COUNT(fullname) AS total FROM emp_grosspay");
$total_employees = mysqli_fetch_assoc($total)['total'];

/* ================= BACK ================= */
if (isset($_POST['back'])) {
    header("Location: admins_view.php");
    exit();
}
?>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" 
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<form method="POST">
    <div id="header">
        <img id="logo" src="../logo.png">
    </div>
    <h1>PAYROLL Management System</h1>

<div id="container">
    <div>
    <div id="row5">
        <label>Search Name:</label>
        <input type="text" name="fullname" placeholder="Enter a name" autocomplete="off">
        <button name="search">SEARCH</button>
    </div>

    <div id="row2">
        <label>Social Security System:</label>
        <input type="text" name="sss" autocomplete="off" value="<?php echo $sss_val; ?>">
        <button name="update_sss">UPDATE</button>
    </div>

    <div id="row3">
        <label>PhilHealth:</label>
        <input type="text" name="philhealth" autocomplete="off" value="<?php echo $philhealth_val; ?>">
        <button name="update_philhealth">UPDATE</button>
    </div>

    <div id="row4">
        <label>Pag-Ibig:</label>
        <input type="text" name="pagibig" autocomplete="off" value="<?php echo $pagibig_val; ?>">
        <button name="update_pagibig">UPDATE</button>
    </div>

    <div id="bot">
        <button  name="update_all">UPDATE ALL</button>
        <button name="back">BACK</button>
    </div>
    </div>

    <div id="employee-count">
        <i class="fa-solid fa-users fa-2xl "style="margin-bottom: 20px;"></i>
        <h3>Total Employees</h3>
        <p><?php echo $total_employees; ?></p>
    </div>
</div>


    <!-- SCROLLABLE TABLE -->
<div class="table-scroll">
    <table>
        <tr>
            <th>FULLNAME</th>
            <th>PAYMENT</th>
            <th>SSS</th>
            <th>PHILHEALTH</th>
            <th>PAG-IBIG</th>
        </tr>

            <?php while($rows = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $rows['fullname']; ?></td>

                    <td>
                        <form method="POST" class="inline-form">
                            <input type="hidden" name="id" value="<?php echo $rows['id']; ?>">
                            <input type="text" name="payment" value="<?php echo $rows['payment']; ?>">
                            <button name="update_payment">UPDATE</button>
                        </form>
                    </td>

                    <td><?php echo $rows['sss']; ?></td>
                    <td><?php echo $rows['philhealth']; ?></td>
                    <td><?php echo $rows['pagibig']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</form>

<style>
*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body{
    min-height: 100vh;
    padding: 20px;
    display: flex;
    flex-direction: column;
    background: url('../background.jpg') no-repeat center center/cover;
}

#row1, #row2, #row3, #row4, #row5{
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    gap: 10px;
}

#container{
    display: flex;
    flex-direction: row;
}

#employee-count{
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    height: 150px;
    width: 200px;
    margin-top: 20px;
    margin-left: 250px;
    background-image: linear-gradient(to right, #629dfa 0%, #c2e9fb 51%, #629dfa 100%);
    background-size: 200% auto;
    font-weight: bold;
    border-radius: 10px;
    border: solid 2px whitesmoke;
    box-shadow: 0 0 20px #eeeeee;
}

h3{
    font-family: Copperplate, "Copperplate Gothic Light", fantasy;
    font-weight: bold;
    font-size: 20px;
    margin-bottom: 10px;
}

p{
    font-family: Copperplate, "Copperplate Gothic Light", fantasy;
    font-weight: bold;
    font-size: 34px;
}

.inline-form{
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}

/* TITLE */
h1{
    text-align: center;
    color: #edeeef;
    font-family: Copperplate, "Copperplate Gothic Light", fantasy;
    text-shadow: 0 0 10px #31a5a9, 0 0 20px #00f7ff;
    margin-bottom: 15px;
}

label{
    display: inline-block;
    width: 150px;  
    color: white;
    font-weight: bold;
    font-family: Copperplate, "Copperplate Gothic Light", fantasy;
}

/* INPUT */
input[type="text"]{
    padding: 8px;
    border-radius: 6px;
    border: 1px solid black;
    outline: none;
    width: 220px;
    box-sizing: border-box;
}
/* LOGO */
#header{
    position: absolute;
    top: 10px;
    left: 275px;

    display: flex;
    align-items: center;
    gap: 10px;

    z-index: 20;
}

#logo{
    width: 100px; 
}

/* BUTTONS */
button{
    padding: 6px 12px;
    margin: 3px;
    font-family: Copperplate, "Copperplate Gothic Light", fantasy;
    font-weight: bold;
    font-size: 14px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    background-image: linear-gradient(to right, #629dfa 0%, #c2e9fb 51%, #629dfa 100%);
    background-size: 200% auto;
    transition: 0.3s;
    box-shadow: 0 0 20px #eeeeee;
}

button:hover{
    background-position: right center;
    transform: translateY(-3px) scale(1.05);
}

#bot{
    margin-left:155px;
    margin-bottom:20px;
}

/* SCROLL TABLE */
.table-scroll{
    width: 100%;
    max-height: 450px;
    overflow-y: auto;
    overflow-x: auto;
    border-radius: 12px;
    background: white;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
}

table{
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
    
}

th{
    position: sticky;
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    top: 0;
    background: linear-gradient(to right, #4e54c8, #8f94fb);
    color: white;
    padding: 12px;
    text-transform: uppercase;
    font-size: 12px;
}

td{
    text-align: center;
    padding: 12px;
    border-bottom: 1px solid #eee;
}

td:first-child{
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    font-weight: bold;
    color: #4e54c8;
    letter-spacing: 1px;
}

tr:nth-child(even){
    background: #f7f9ff;
}

tr:hover{
    background: #eaf0ff;
}
</style>
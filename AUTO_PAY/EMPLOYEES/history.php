<?php
session_start();
date_default_timezone_set('Asia/Manila');
include "db.php";

$msg = "";

/* ================= SET EMPLOYEE (SESSION) ================= */
if (isset($_GET['fullname'])) {
    $_SESSION['fullname'] = $_GET['fullname'];
}

$fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : "";

/* ================= GET MESSAGE ================= */
if (!empty($fullname)) {
    $msg = "Monthly Payroll Record of " . $fullname;
}

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

    mysqli_query($conn, "
        INSERT INTO history (
            fullname, payment, sss, philhealth, pagibig,
            days, total_days_pay, total_payment,
            month, year, receipt_status, generated_at
        )
        VALUES (
            '$fullname','$payment','$sss','$philhealth','$pagibig',
            '$days','$total_days_pay','$total_payment',
            '$month','$year','Received', NOW()
        )
    ");
}

/* ================= SEARCH / FILTER ================= */
if (isset($_POST['search_bar'])) {

    $search_month = $_POST['search_month'];
    $search_year = $_POST['search_year'];

    $result = mysqli_query($conn, "
        SELECT * 
        FROM history
        WHERE fullname='$fullname'
        AND month='$search_month'
        AND year='$search_year'
        ORDER BY id DESC
    ");

} else {

    $result = mysqli_query($conn, "
        SELECT * 
        FROM history
        WHERE fullname='$fullname'
        ORDER BY id DESC
    ");
}
?>


<div id="header">
        <img id="logo" src="../logo.png">
    </div>
<h2><?php echo $msg; ?></h2>

<form method="POST" id="srch">
    <div id="row1">
        <label id="month_m">SEARCH MONTH (1-12): </label> 
        <input type="text" name="search_month" placeholder="Enter month number" ><br>
    </div>
    
    <div id="row2">
        <label id="year_y">YEAR: </label>
        <input type="text" name="search_year" placeholder="Enter year" ><br>
    </div>

    <div id="bot">
        <button  type="submit" name="search_bar">SEARCH</button>
        <button  type="submit" name="reset">Show All</button>
    </div>
</form>

<div class="table-scroll">
    <table>
        <tr>
            <th>SALARY</th>
            <th>SSS</th>
            <th>PHILHEALTH</th>
            <th>PAG-IBIG</th>
            <th>MONTH - YEAR</th>
            <th>DAYS OF WORK</th>
            <th>TOTAL DAYS PAYMENT</th>
            <th>NET SALARY</th>
            <th>STATUS</th>
            <th>DATE RECEIVE</th>
            <th>RECEIPT</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($result)){ ?>

        <tr>
            <td><?php echo $row['payment']; ?></td>
            <td><?php echo $row['sss']; ?></td>
            <td><?php echo $row['philhealth']; ?></td>
            <td><?php echo $row['pagibig']; ?></td>
            <td><?php echo $row['month']." - ".$row['year']; ?></td>
            <td><?php echo $row['days']; ?></td>
            <td><?php echo $row['total_days_pay']; ?></td>
            <td><?php echo $row['total_payment']; ?></td>

            <td style="color:
                <?php echo ($row['receipt_status']=="Received") ? "green" : "red"; ?>">
                <?php echo $row['receipt_status']; ?>
            </td>

            <!--  FIXED DATE DISPLAY -->
            <td>
                <?php 
                if(!empty($row['generated_at'])){
                    echo date("F d, Y h:i A", strtotime($row['generated_at']));
                } else {
                    echo date("F d, Y h:i A"); // fallback (current date)
                }
                ?>
            </td>

            <td>
                <a href="generate_receipt.php?id=<?php echo $row['id']; ?>" target="_blank">
                    <button id="receipt">
                        RECEIPT
                    </button>
                </a>
            </td>
        </tr>

    <?php } ?>

    </table>
</div>

<br>

<form method="POST">
    <button type="button" id="back"
    onclick="location.href='employees_view.php?fullname=<?php echo urlencode($fullname); ?>'">
    Back
    </button>
</form>

<style>
*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body{
    height: 100vh;
    padding: 30px;
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

#header{
    position: absolute;
    top: 10px;
    left: 150px;

    display: flex;
    align-items: center;
    gap: 10px;

    z-index: 20;
}

#logo{
    width: 120px; 
}

/* TITLE */
h2{
    text-align: center;
    font-size:30px;
    color: #edeeef;
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    text-shadow: 0 0 10px #31a5a9, 0 0 20px #00f7ff;
    margin-bottom: 20px;
}

/*SEARCH*/
#row1, #row2{
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    gap: 10px;
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

#month_m{
    width: 200px;   
    color: white;
    font-weight: bold;
    font-family: Copperplate, "Copperplate Gothic Light", fantasy;
}

#year_y{
    margin-left: 150px;
    color: white;
    font-weight: bold;
    font-family: Copperplate, "Copperplate Gothic Light", fantasy;
}

#bot{
    margin-left:220px;
    margin-bottom: 10px;
    margin-top: 10px;
}


/* TABLE WRAPPER */
.table-scroll{
    max-height: 400px;
    overflow-y: auto;
    border-radius: 12px;
    background: white;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
}

/* TABLE */
table{
    width: 100%;
    border-collapse: collapse;
}

/* HEADER */
th{
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    position: sticky;
    top: 0;
    background: linear-gradient(to right, #4e54c8, #8f94fb);
    color: white;
    padding: 12px;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1px;
    z-index: 2;
}

/* CELLS */
td{
    padding: 12px;
    font-size: 15px;
    text-align: center;
    border: 1px solid #0d0d15;
    font-family: Copperplate, "Copperplate Gothic Light", fantasy;
    font-weight: bold;

}

td:nth-child(8){
    color: #329038;
    letter-spacing: 1px;
}

tr:nth-child(even){
    background: #f7f9ff;
}

tr:hover{
    background: #eaf0ff;
    transition: 0.2s;
}


td:nth-child(10){
    font-weight: bold;
}

td:contains("Received"){
    color: green;
}

.received{
    color: green;
    font-weight: bold;
}

.not-received{
    color: red;
    font-weight: bold;
}

/* BUTTONS */
button{
    padding: 6px 12px; 
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    font-weight: bold;
    font-size: 15px;

    border-radius: 10px;
    border: none;

    background: linear-gradient(to right, #629dfa 0%, #c2e9fb 51%, #629dfa 100%);
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

#receive{
    padding: 6px 12px; 
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    font-weight: bold;
    font-size: 15px;

    border-radius: 10px;
    border: none;

    background: linear-gradient(to right, #0f9518 0%, #c2e9fb 51%, #089e14 100%);
    background-size: 200% auto;

    cursor: pointer;
    transition: 0.3s;

    box-shadow: 0 0 20px #eeeeee;
}

#receive:hover{
    background-position: right center;
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 10px 20px #129401;
}


#receipt{
    padding: 6px 12px; 
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    font-weight: bold;
    font-size: 15px;

    border-radius: 10px;
    border: none;

    background: linear-gradient(to right, #629dfa 0%, #c2e9fb 51%, #629dfa 100%);
    background-size: 200% auto;

    cursor: pointer;
    transition: 0.3s;

    box-shadow: 0 0 20px #eeeeee;
}

#receipt:hover{
    background-position: right center;
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 10px 20px #0372bd;
}

#back{
    display: block;
    margin: 20px auto;
    height: 60px;
    width: 120px;
}
</style>
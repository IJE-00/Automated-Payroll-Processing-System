<?php
date_default_timezone_set('Asia/Manila');
include "db.php";

/* ================= AUTO-GENERATE HISTORY (IF NOT EXISTS) ================= */
if(!empty($fullname)){


    $check = mysqli_query($conn,"
        SELECT * FROM history 
        WHERE fullname='$fullname' 
        AND month='$prev_month' 
        AND year='$prev_year'
    ");

    if(mysqli_num_rows($check) == 0){

        $default = mysqli_query($conn,"SELECT * FROM emp_grosspay LIMIT 1");
        $def = mysqli_fetch_assoc($default);

        $payment = $def['payment'] ?? 0;
        $sss = $def['sss'] ?? 0;
        $philhealth = $def['philhealth'] ?? 0;
        $pagibig = $def['pagibig'] ?? 0;

        $att = mysqli_query($conn,"
            SELECT COUNT(*) as days 
            FROM attendance
            WHERE fullname='$fullname'
            AND status='Present'
            AND MONTH(date)='$prev_month'
            AND YEAR(date)='$prev_year'
        ");

        $att_data = mysqli_fetch_assoc($att);
        $days = $att_data['days'] ?? 0;

        $total_days_pay = $payment * $days;
        $total_payment = $total_days_pay - ($sss + $philhealth + $pagibig);

        mysqli_query($conn,"
            INSERT INTO history
            (fullname, payment, sss, philhealth, pagibig,
            days, total_days_pay, total_payment,
            month, year, receipt_status, generated_at)
            VALUES
            ('$fullname','$payment','$sss','$philhealth','$pagibig',
            '$days','$total_days_pay','$total_payment',
            '$prev_month','$prev_year','Not Received', NOW())
        ");
    }
}

/* ================= SUBMIT RECEIPT ================= */
if(isset($_POST['submit_receipt'])){

    $fullname = $_POST['fullname'];
    $payment = $_POST['payment'];
    $sss = $_POST['sss'];
    $philhealth = $_POST['philhealth'];
    $pagibig = $_POST['pagibig'];
    $days = $_POST['days'];
    $total_days_pay = $_POST['total_days_pay'];
    $total_payment = $_POST['total_payment'];
    $month = $_POST['month'];
    $year = $_POST['year'];

    mysqli_query($conn,"
        INSERT INTO history
        (fullname, payment, sss, philhealth, pagibig,
        days, total_days_pay, total_payment,
        month, year, receipt_status, generated_at)
        VALUES
        ('$fullname','$payment','$sss','$philhealth','$pagibig',
        '$days','$total_days_pay','$total_payment',
        '$month','$year','Received', NOW())
    ");
}

/* ================= DELETE RECORD ================= */
if(isset($_POST['delete'])){
    $id = intval($_POST['id']);
    mysqli_query($conn, "DELETE FROM history WHERE id='$id'");
}

if(isset($_POST['delete_all'])){
    mysqli_query($conn, "DELETE FROM history");
}

/* ================= SEARCH HISTORY ================= */
if(isset($_POST['search']) && !empty($_POST['search_name'])){
    
    $search = $_POST['search_name'];

    $result = mysqli_query($conn,"
        SELECT * FROM history
        WHERE fullname LIKE '%$search%'
        ORDER BY id DESC
    ");

} else {
    $result = mysqli_query($conn,"
        SELECT * FROM history
        ORDER BY id DESC
    ");
}

/* ================= RESET VIEW ================= */
if(isset($_POST['reset'])){
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}
?>

<div id="logo-box">
        <img id="logo" src="../logo.png">
    </div>
<h1>PAYOUT RECORDS</h1>

<form method="POST" style="margin-bottom:15px;">
    <input type="text" name="search_name" placeholder="Search fullname..." 
           value="<?php echo isset($_POST['search_name']) ? $_POST['search_name'] : ''; ?>">

    <button id="search" type="submit" name="search">Search</button>
    <button id="show" type="submit" name="reset">Show All</button>
</form>

<div class="table-scroll">
    <table border="1" cellpadding="10">
        <tr>
            <th>FULLNAME</th>
            <th>PAYMENT</th>
            <th>SSS</th>
            <th>PHILHEALTH</th>
            <th>PAG-IBIG</th>
            <th>MONTH - YEAR</th>
            <th>DAYS OF WORK</th>
            <th>TOTAL DAYS PAYMENT</th>
            <th>OVERALL PAYMENT</th>
            <th>STATUS</th>
            <th>DATE GENERATED</th>
            <th>ACTION</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($result)){ ?>

        <tr>
            <td><?php echo $row['fullname']; ?></td>
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

            <td>
                <?php 
                if(!empty($row['generated_at'])){
                    echo date("F d, Y h:i A", strtotime($row['generated_at']));
                } else {
                    echo date("F d, Y h:i A");
                }
                ?>
            </td>

            <td>
                <form method="POST" style="margin:0;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button type="submit" id="delete" name="delete">DELETE</button>
                </form>
            </td>
        </tr>
    <?php } ?>

    </table>
</div>
<br>

<form method="POST" id="bots">
    <button id="del_all" type="submit" name="delete_all">DELETE ALL</button>
    <button id="back" type="button" onclick="location.href='admins_view.php'">Back</button>
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
    background: url('../background.jpg') no-repeat center center/cover;
}

/* LOGO */
#logo-box{
    position: absolute;
    top: 15px;
    left: 365px;

    display: flex;
    align-items: center;
    gap: 10px;

    z-index: 20; 
}

#logo{
    width: 120px; 
}

/* TITLE */
h1{
    text-align: center;
    font-size: 30px;
    font-family: Copperplate, "Copperplate Gothic Light", fantasy;
    color: #edeeef;
    text-shadow: 0 0 10px #31a5a9, 0 0 20px #00f7ff;
    margin-bottom: 20px;
}

/* SEARCH FORM */
form{
    text-align: center;
    margin-bottom: 15px;
}

input[type="text"]{
    padding: 10px;
    border-radius: 6px;
    border: 2px solid black;
    outline: none;
}

/* TABLE WRAPPER (IMPORTANT FIX) */
.table-scroll{
    max-height: 350px;
    overflow-y: auto;
    overflow-x: auto;
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
    text-align: center;
    font-weight: bold;
    border-bottom: 1px solid #eee;
    font-size: 14px;
}

td:first-child{
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    font-weight: bold;
    color: #4e54c8;
    letter-spacing: 1px;
}

td:nth-child(9){
    font-weight: bold;
    color:green;
}

/* ROW EFFECT */
tr{
    background: #f7f9ff;
}

tr:hover{
    background: #eaf0ff;
    transition: 0.2s;
}



/* STATUS COLORS (FIXED) */
td:nth-child(10){
    font-weight: bold;
}

td:nth-child(10)[style*="green"]{
    color: green !important;
}

td:nth-child(10)[style*="red"]{
    color: red !important;
}

/* BUTTONS */
#search, #show, #back, #del_all{
    padding: 5px 11px;  
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    font-weight: bold;
    font-size: 20px;

    border-radius: 10px;
    border: none;

    background: linear-gradient(to right, #629dfa 0%, #c2e9fb 51%, #629dfa 100%);
    background-size: 200% auto;

    cursor: pointer;
    transition: 0.3s;

    box-shadow: 0 0 20px #eeeeee;
}

#search:hover, #show:hover, #back:hover, #del_all:hover{
    background-position: right center;
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 10px 20px #0372bd;
}

#bots{
    display: flex;
    justify-content: center;
    gap: 20px;
}

/*DELETE */
#delete{
    padding: 6px 12px; 
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    font-weight: bold;
    font-size: 15px;

    border-radius: 10px;
    border: none;

    background: linear-gradient(to right, #f51c1c 0%, #fbc2c2 51%, #b31a1a 100%);
    background-size: 200% auto;

    cursor: pointer;
    transition: 0.3s;

    box-shadow: 0 0 10px #ff8383;
}

#delete:hover{
    background-position: right center;
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 10px 10px #f30606;
}
</style>
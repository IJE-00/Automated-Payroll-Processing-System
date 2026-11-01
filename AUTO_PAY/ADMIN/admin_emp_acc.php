<form method="POST">
    <div id="logo-box">
        <img id="logo" src="../logo.png">
    </div>
    <h1>Employee Account Management System</h1>

    <label>SEARCH: </label>
    <input type="text" name="fullname" placeholder="Enter a name...">

    <button name="search">SEARCH</button>
    <button type="button" onclick="location.href='admins_view.php'">BACK</button>
</form>


<?php
include "db.php";

/* ================= ACTIONS ================= */
if(isset($_POST['action'])){

    $id = $_POST['id'];

    //GET fullname from DB using ID
    $getUser = mysqli_query($conn, "SELECT fullname FROM account WHERE id='$id'");
    $userData = mysqli_fetch_assoc($getUser);

    if(!$userData){
        die("User not found");
    }

    $fullname = $userData['fullname'];

    /*ACTIVATE */
    if($_POST['action'] == 'activate'){
        mysqli_query($conn,
        "UPDATE account
        SET status='ACTIVATE', attempts=0
        WHERE id='$id'");
    }
    /*DEACTIVATE */
    if($_POST['action'] == 'deactivate'){
        mysqli_query($conn,
        "UPDATE account
        SET status='DEACTIVATE', attempts=0
        WHERE id='$id'");
    }

/* ================= DELETE ================= */
    if($_POST['action'] == 'delete'){

        // DELETE account first
        mysqli_query($conn,
        "DELETE FROM account WHERE id='$id'");

        // DELETE related data using SAME fullname
        mysqli_query($conn,
        "DELETE FROM attendance WHERE fullname='$fullname'");

        mysqli_query($conn,
        "DELETE FROM emp_grosspay WHERE fullname='$fullname'");

        mysqli_query($conn,
        "DELETE FROM history WHERE fullname='$fullname'");
    }
}

/* ================= SEARCH ================= */
if(isset($_POST['search'])){

    $fullname = $_POST['fullname'];

    $result = mysqli_query($conn,
    "SELECT * FROM account
    WHERE fullname LIKE '%$fullname%'");

}else{

    $result = mysqli_query($conn,
    "SELECT * FROM account
    ORDER BY fullname ASC");

}


/*TABLE*/
echo "<div class='table-scroll'>";
    echo "<table>";

    echo "<tr>
            <th>FULLNAME</th>
            <th>ATTEMPTS</th>
            <th>STATUS</th>
            <th>ACTION</th>
            <th>REMOVE</th>
        </tr>";

    while($rows = mysqli_fetch_assoc($result)){

        echo "<tr>";
        echo "<td>".$rows['fullname']."</td>";
        echo "<td>".$rows['attempts']."</td>";
        $statusClass = ($rows['status'] == 'ACTIVATE') ? 'active' : 'inactive';
        echo "<td class='$statusClass'>".$rows['status']."</td>";
        echo "<form method='POST' style='display:inline;'>";
        echo "<td>
                <input type='hidden' name='id' value='".$rows['id']."'>
                <button type='submit' name='action' id='activate' value='activate'> Activate</button>
                <button type='submit' name='action' id='deactivate' value='deactivate'> Deactivate </button>
            </td>";
        echo "<td>
                <input type='hidden' name='id' value='".$rows['id']."'>
                <button type='submit' name='action' id='delete' value='delete'> Delete </button>
            </td>";
        echo "</form>";
        echo "</tr>";
    }
    echo "</table>";
echo "</div>";
?>


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
    align-items: center;
    background: url('../background.jpg') no-repeat center center/cover;
}


#logo-box{
    position: absolute;
    top: 10px;
    left: 200px;

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
    color: #edeeef;
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    text-shadow: 0 0 10px #31a5a9, 0 0 20px #00f7ff;
    margin-bottom: 15px;
}

/* SEARCH FORM */
form{
    text-align: center;
    margin-bottom: 15px;
}

label{
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    color: white;
    font-weight: bold;
}

/* INPUT */
input[type="text"]{
    padding: 8px;
    border-radius: 6px;
    border: 1px black solid;
    outline: none;
}

/* BUTTONS */
button{
    padding: 6px 12px;
    margin: 3px;
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    font-weight: bold;
    font-size: 15px;

    border-radius: 10px;
    border: none;

    background-image: linear-gradient(to right, #629dfa 0%, #c2e9fb 51%, #629dfa 100%);
    background-size: 200% auto;

    cursor: pointer;
    transition: 0.3s;

    box-shadow: 0 0 20px #eeeeee;
}

/* GENERAL HOVER */
button:hover{
    background-position: right center;
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 10px 20px #0372bd;
}

/* TABLE WRAPPER */
.table-scroll{
    width: 95%;
    max-height: 500px;
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
    position: sticky;
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    top: 0;
    background: linear-gradient(to right, #4e54c8, #8f94fb);
    color: white;
    padding: 12px;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* CELLS */
td{
    text-align: center;
    font-weight: bold;
    padding: 12px;
    border-bottom: 1px solid #eee;
}

/* ROW EFFECT */
tr:nth-child(even){
    background: #f7f9ff;
}

tr:hover{
    background: #eaf0ff;
}


/* STATUS COLORS */
td:first-child{
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    font-weight: bold;
    color: #4e54c8;
    letter-spacing: 1px;
}

td:nth-child(3){
    font-weight: bold;
}

.active{
    color: #05720a;
    font-weight: bold;
}

.inactive{
    color: #b62e05;
    font-weight: bold;
}

/* ACTIVATE BUTTON */
#activate{
    padding: 6px 12px; 
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    font-weight: bold;
    font-size: 15px;

    border-radius: 10px;
    border: none;

    background: linear-gradient(to right, #0fd019 0%, #c2fbcc 51%, #03941e 100%);
    background-size: 200% auto;

    cursor: pointer;
    transition: 0.3s;

    box-shadow: 0 0 20px #8af58f;
}

#activate:hover{
    background-position: right center;
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 10px 10px #03bd16;
}

/*DEACTIVATE */

#deactivate{
    padding: 6px 12px; 
    font-family:Copperplate, "Copperplate Gothic Light", fantasy;
    font-weight: bold;
    font-size: 15px;

    border-radius: 10px;
    border: none;

    background: linear-gradient(to right, #e97816 0%, #fbe6c2 51%, #c67e0a 100%);
    background-size: 200% auto;

    cursor: pointer;
    transition: 0.3s;

    box-shadow: 0 0 10px #ffbb83;
}

#deactivate:hover{
    background-position: right center;
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 10px 10px #bd4703;
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
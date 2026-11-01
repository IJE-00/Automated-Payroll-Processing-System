<?php
require 'dompdf/autoload.inc.php';
include "db.php";

use Dompdf\Dompdf;

date_default_timezone_set('Asia/Manila');

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $historyQuery = mysqli_query($conn, "
        SELECT * FROM history
        WHERE id='$id'
        LIMIT 1
    ");

    $history = mysqli_fetch_assoc($historyQuery);

    if (!$history) {
        die("No history record found");
    }

    $payment = $history['payment'];
    $sss = $history['sss'];
    $philhealth = $history['philhealth'];
    $pagibig = $history['pagibig'];

    $monthName = date("F", mktime(0, 0, 0, $history['month'], 10));
    $year = $history['year'];

    $generatedDate = !empty($history['generated_at'])
        ? date("F d, Y h:i A", strtotime($history['generated_at']))
        : date("F d, Y h:i A");

    /*  */
    $html = "
        <h2 style='text-align:center;'>PAYROLL RECEIPT</h2>
        <hr>

        <p><b>Name:</b> {$history['fullname']}</p>
        <p><b>Month:</b> {$monthName} {$year}</p>
        <p><b>Date Generated:</b> {$generatedDate}</p>

        <table border='1' width='100%' cellpadding='8'>
            <tr>
                <th>Gross Payment</th>
                <th>SSS</th>
                <th>PhilHealth</th>
                <th>Pag-IBIG</th>
            </tr>
            <tr>
                <td>{$payment}</td>
                <td>{$sss}</td>
                <td>{$philhealth}</td>
                <td>{$pagibig}</td>
            </tr>
        </table>

        <br>

        <p><b>Days Worked:</b> {$history['days']}</p>
        <p><b>Total Days Payment:</b> {$history['total_days_pay']}</p>

        <h3><b>Net Salary:</b> {$history['total_payment']}</h3>
    ";

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $dompdf->stream(
        "RECEIPT_{$id}.pdf",
        ["Attachment" => 0]
    );
}
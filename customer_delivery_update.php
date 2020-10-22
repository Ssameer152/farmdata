<?php
session_start();

if (isset($_SESSION['user'])) {
    include_once 'db.php';
    if (isset($_POST['qty']) && $_POST['qty'] != '' && isset($_POST['dlqty']) && $_POST['dlqty'] != '' && isset($_POST['csid']) && isset($_POST['cid'])) {
        $qty = mysqli_real_escape_string($db, $_POST['qty']);
        $dlqty = mysqli_real_escape_string($db, $_POST['dlqty']);
        $cid = mysqli_real_escape_string($db, $_POST['cid']);
        $csid = mysqli_real_escape_string($db, $_POST['csid']);
        $returnpage = mysqli_real_escape_string($db, $_POST['returnpage']);

        if (isset($_POST['mid']) && $_POST['mid'] != '') {
            $mid = $_POST['mid'];
            "UPDATE sales.commissions SET sales.commissions.commission = c.base_amount * t.percentage 
            FROM customer_delivery_log c
            INNER JOIN sales.targets t 
            ON c.target_id = t.target_id";
            $r = mysqli_query($db, $q);

            $msg = 'Updated';
        } else {
            $msg = "Please Login";
            echo <<<_END
            <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
        _END;
        }
    }
}

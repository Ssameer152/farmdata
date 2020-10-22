<?php

session_start();
if (isset($_SESSION['user'])) {
    include_once 'db.php';
    if (
        isset($_POST['cow_qty']) && $_POST['cow_qty'] != ''
        && isset($_POST['sahi_qty']) && $_POST['sahi_qty'] != ''
        && isset($_POST['buf_qty']) && $_POST['buf_qty'] != ''
        && isset($_POST['cow_mlk_qty']) && $_POST['cow_mlk_qty'] != ''
        && isset($_POST['sahi_mlk-qty']) && $_POST['sahi_mlk-qty'] != ''
        && isset($_POST['buf_mlk_qty']) && $_POST['buf_mlk_qty'] != ''
        && isset($_POST['sub_hide'])
        && isset($_POST['csid_hide'])
    ) {

        $cow_qty = mysqli_real_escape_string($db, $_POST['cow_qty']);
        $sahi_qty = mysqli_real_escape_string($db, $_POST['sahi_qty']);
        $buf_qty = mysqli_real_escape_string($db, $_POST['buf_qty']);
        $cow_mlk_qty = mysqli_real_escape_string($db, $_POST['cow_mlk_qty']);
        $sahi_mlk_qty = mysqli_real_escape_string($db, $_POST['sahi_mlk_qty']);
        $buf_mlk_qty = mysqli_real_escape_string($db, $_POST['buf_mlk_qty']);
        $sub_hide = mysqli_real_escape_string($db, $_POST['sub_hide']);
        $csid_hide = mysqli_real_escape_string($db, $_POST['csid_hide']);

        $q = "UPDATE customer_delivery_log cdl JOIN customer_subscription cs
            ON  cdl.csid = cs.id
            SET delivered_qty = CASE WHEN cs.milktype=1 and cs.delivery_time=1 THEN $cow_qty END,
                delivered_qty = CASE WHEN cs.milktype=2 and cs.delivery_time=1 THEN  $sahi_qty END,
                delivered_qty = CASE WHEN cs.milktype=3 and cs.delivery_time=1 THEN $buf_qty END,
                delivered_qty = CASE WHEN cs.milktype=1 and cs.delivery_time=2 THEN $cow_mlk_qty END,
                delivered_qty = CASE WHEN cs.milktype=2 and cs.delivery_time=2 THEN $sahi_mlk_qty END,
                delivered_qty = CASE WHEN cs.milktype=3 and cs.delivery_time=2 THEN $buf_mlk_qty END 
                WHERE csid='$csid_hide'";
        $r = mysqli_query($db, $q);
        $msg = 'Updated';
    }
} else {
    $msg = "Please Login";
    echo <<<_END
        <meta http-equiv='refresh' content='0;url=?msg=$msg'>
    _END;
}

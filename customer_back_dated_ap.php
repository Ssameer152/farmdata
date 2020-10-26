<?php
session_start();

if (isset($_SESSION['user'])) {
    include_once 'db.php';
    if (isset($_POST['sDate']) && $_POST['sDate'] != '' && isset($_POST['dlqty']) && $_POST['dlqty'] != '' && isset($_POST['id_hide']) && isset($_POST['cid'])) {
        $sdate = mysqli_real_escape_string($db, $_POST['sDate']);
        $dlqty = mysqli_real_escape_string($db, $_POST['dlqty']);
        $cid = mysqli_real_escape_string($db, $_POST['cid']);
        $id_hide = mysqli_real_escape_string($db, $_POST['id_hide']);
        if (isset($_POST['add'])) {
            $q = "INSERT INTO customer_delivery_log(dod , delivered_qty , cid , csid) VALUES('$sdate','$dlqty','$cid','$id_hide')";
            $r = mysqli_query($db, $q);
            $msg = "Customer delivery log Added";
            echo <<<_END
        <meta http-equiv='refresh' content='0;url=customer_back_dated.php?msg=$msg'>
_END;
        }
    }
} else {
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}

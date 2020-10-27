<?php
session_start();
function getDimensionValue($db, $table, $gid, $name)
{
    $q = "SELECT * FROM $table WHERE id=$gid";
    $r = mysqli_query($db, $q);

    $res = mysqli_fetch_assoc($r);

    $value = $res[$name];

    return $value;
}

if (isset($_SESSION['user'])) {
    include_once 'db.php';
    if (isset($_POST['sDate']) && $_POST['sDate'] != '' && isset($_POST['dlqty']) && $_POST['dlqty'] != '' && isset($_POST['id_hide']) && isset($_POST['cid']) && isset($_POST['sub_qty'])) {
        $sdate = mysqli_real_escape_string($db, $_POST['sDate']);
        $dlqty = mysqli_real_escape_string($db, $_POST['dlqty']);
        $cid = mysqli_real_escape_string($db, $_POST['cid']);
        $id_hide = mysqli_real_escape_string($db, $_POST['id_hide']);
        $sub_qty = mysqli_real_escape_string($db, $_POST['sub_qty']);
        if (isset($_POST['add'])) {
            $q = "INSERT INTO customer_delivery_log(dod , qty , delivered_qty , cid , csid) VALUES('$sdate','$sub_qty','$dlqty','$cid','$id_hide')";
            $r = mysqli_query($db, $q);
            $msg = "Customer delivery log Added";

            echo <<<_END
                    <meta http-equiv='refresh' content='0;url=customer_back_dated.php?msg=$msg&start_date=$sdate'>
_END;
        }
    }
} else {
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}
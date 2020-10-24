<?php
function getDimensionValue($db, $table, $gid, $name)
{
    $q = "SELECT * FROM $table WHERE id=$gid";
    $r = mysqli_query($db, $q);

    $res = mysqli_fetch_assoc($r);

    $value = $res[$name];

    return $value;
}
session_start();
if (isset($_SESSION['user'])) {
    include_once 'db.php';
    if (
        isset($_POST['dlqty']) && $_POST['dlqty'] != '' && isset($_POST['id_hide']) && isset($_POST['start_date'])
        && isset($_POST['end_date']) && isset($_POST['cid'])
    ) {
        $del_qty = mysqli_real_escape_string($db, $_POST['dlqty']);
        $id_hide = mysqli_real_escape_string($db, $_POST['id_hide']);
        $cid = mysqli_real_escape_string($db, $_POST['cid']);
        $start_date = mysqli_real_escape_string($db, $_POST['start_date']);
        $end_date = mysqli_real_escape_string($db, $_POST['end_date']);
        if ($id_hide) {
            $alert = "select * from customer_delivery_log where id='$id_hide'";
            $r = mysqli_query($db, $alert);
            $res = mysqli_fetch_array($r);
            $a = getDimensionValue($db, 'customer_subscription', $res['csid'], 'milktype');
            $b = getDimensionValue($db, 'customer', $res['cid'], 'fname');
        }
        $q = "UPDATE customer_delivery_log SET delivered_qty ='$del_qty' WHERE id='$id_hide' limit 1";
        $r = mysqli_query($db, $q);
        $msg = 'Updated';
        if ($a == 1) {
            echo <<<_END
        <meta http-equiv='refresh' content='0;url=customer_delivery_preview.php?start_date=$start_date&end_date=$end_date&msg=Cow Milk Delivered Quantity of $b has been $msg'>
_END;
        } elseif ($a == 2) {
            echo <<<_END
        <meta http-equiv='refresh' content='0;url=customer_delivery_preview.php?start_date=$start_date&end_date=$end_date&msg=Sahiwal Milk Delivered Quantity of $b has been $msg'>
_END;
        } elseif ($a == 3) {
            echo <<<_END
    <meta http-equiv='refresh' content='0;url=customer_delivery_preview.php?start_date=$start_date&end_date=$end_date&msg=Buffalo Milk Delivered Quantity of $b has been $msg'>
_END;
        }
    }
} else {
    $msg = "Please Login";
    echo <<<_END
        <meta http-equiv='refresh' content='0;url=?msg=$msg'>
_END;
}
?>
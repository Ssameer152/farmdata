<?php
session_start();
if (isset($_SESSION['submit'])) {
    include_once 'db.php';
    if (isset($_POST['sd']) && isset($_POST['sd']) != '' && isset($_POST['meeting']) && isset($_POST['meeting']) != '' && isset($_POST['activity']) && isset($_POST['activity']) != '' && isset($_POST['person']) && isset($_POST['person']) != '' && isset($_POST['resource']) && isset($_POST['resource']) != '' && isset($_POST['cmq']) && isset($_POST['cmq']) != '' && isset($_POST['smq']) && isset($_POST['smq']) != '' && isset($_POST['bmq']) && isset($_POST['bmq']) != '' && isset($_POST['qty']) && isset($_POST['qty']) != '') {
        $sd = $_POST['sd'];
        $meeting = $_POST['meeting'];
        $activity = $_POST['activity'];
        $person = $_POST['person'];
        $resource = $_POST['resource'];
        $cmq = $_POST['cmq'];
        $smq = $_POST['smq'];
        $bmq = $_POST['bmq'];
        $qty = $_POST['qty'];

        $q = "INSERT INTO common_work(activity , person , start_dates , cowmilk_qty , buffalosmilk_qty , sahiwalmilk_qty , quantity, meeting_time) VALUES('$activity','$person','$sd','$cmq','$bmq','$smq','$qty','$meeting')";
        $r = mysqli_query($db, $q);
        $msg = "details Added";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=add_common_work.php?msg=$msg'>
_END;
    } else {
        $msg = "Please enter the details";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=add_common_work.php?msg=$msg'>   
_END;
    }
} else {
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}

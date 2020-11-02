<?php
session_start();

if (isset($_SESSION['user'])) {
    include_once 'db.php';

    if (isset($_GET['table']) && isset($_GET['rid']) && isset($_GET['return']) && $_GET['table'] != '' && $_GET['rid'] != '' && $_GET['return'] != '') {
        $table = $_GET['table'];
        $rid = $_GET['rid'];
        $return = $_GET['return'];

        if (isset($_GET['logid'])) {
            $logid = $_GET['logid'];
        } else {
            $logid = '';
        }

        $q = "UPDATE $table SET is_deleted=1 WHERE id='$rid' LIMIT 1";
        $r = mysqli_query($db, $q);

        $msg = 'Record Deleted';
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=$return.php?logid=$logid&msg=$msg'>
_END;
    } else {
        $msg = "Something Went Wrong!!";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
    }
} else {
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}

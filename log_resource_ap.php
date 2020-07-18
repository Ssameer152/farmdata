<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    
    if(isset($_POST['area']) && $_POST['area']!='' && isset($_POST['qty']) && $_POST['qty']!='' && isset($_POST['cpu']) && $_POST['cpu']!='' && isset($_POST['logid']) && $_POST['logid']!='')
    {
        $area = mysqli_real_escape_string($db,$_POST['area']);
        $qty = mysqli_real_escape_string($db,$_POST['qty']);
        $cpu = mysqli_real_escape_string($db,$_POST['cpu']);
        $logid = mysqli_real_escape_string($db,$_POST['logid']);
        
        $q = "INSERT INTO log_resource(logid,resourceid,qty,costperunit) VALUES('$logid','$area','$qty','$cpu')";
        $r = mysqli_query($db,$q);
        
        $msg = "Work Log Added";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=log_resource.php?logid=$logid&msg=$msg'>
_END;
    }
    else
    {
        $msg = "Please select all the fields";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=logs.php?msg=$msg'>
_END;
    }
}
else
{
    $msg = "Please login!";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}

?>
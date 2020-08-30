<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    
    if(isset($_POST['area']) && $_POST['area']!='' && isset($_POST['qty']) && $_POST['qty']!='' && isset($_POST['logid']) && $_POST['logid']!='')
    {
        $area = mysqli_real_escape_string($db,$_POST['area']);
        $qty = mysqli_real_escape_string($db,$_POST['qty']);
        $logid = mysqli_real_escape_string($db,$_POST['logid']);

        $q = "INSERT INTO estimate_log_output(logid,resourceid,qty) VALUES('$logid','$area','$qty')";
        $r = mysqli_query($db,$q);
        
        if(!$r){
            echo mysqli_error($db);
        }
        
        $msg = "Log Added";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=log_estimate_output.php?logid=$logid&msg=$msg'>
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
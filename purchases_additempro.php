<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_POST['resource']) && $_POST['resource']!='' && isset($_POST['qty']) && isset($_POST['cpu']) && isset($_POST['pid']) && $_POST['qty']!='' && $_POST['cpu']!='' && $_POST['pid']!='')
    {
        $resource = mysqli_real_escape_string($db,$_POST['resource']);
        $qty = mysqli_real_escape_string($db,$_POST['qty']);
        $cpu = mysqli_real_escape_string($db,$_POST['cpu']);
        $pid = mysqli_real_escape_string($db,$_POST['pid']);
        
        $q = "INSERT INTO purchase_items(resourceid,qty,costperunit,pid) VALUES('$resource','$qty','$cpu','$pid')";
        $r = mysqli_query($db,$q);
        
        if(!$r){
            echo mysqli_error($db);
        }
        
        $msg = "Added";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=purchases_additem.php?pid=$pid&msg=$msg'>
_END;
        
    }
    else
    {
        $msg = "Please enter the details";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=resources.php?msg=$msg'>   
_END;
    }
}
else
{
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}

?>
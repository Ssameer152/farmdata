<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    
    if(isset($_POST['dop']) && isset($_POST['delivery_status']) && isset($_POST['payment_status']) && isset($_POST['vendor']) && $_POST['dop']!='' && $_POST['delivery_status']!='' && $_POST['payment_status']!='' && $_POST['vendor']!='')
    {
        $dop = mysqli_real_escape_string($db,$_POST['dop']);
        $delivery_status = mysqli_real_escape_string($db,$_POST['delivery_status']);
        $payment_status = mysqli_real_escape_string($db,$_POST['payment_status']);
        $vendor = mysqli_real_escape_string($db,$_POST['vendor']);
        
        $q = "INSERT INTO purchases(dop,vendorid,delivery_status,payment_status) VALUES('$dop','$vendor','$delivery_status','$payment_status')";
        $r = mysqli_query($db,$q);
        
        $msg = "Record Added";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=purchases.php?msg=$msg'>
_END;
        
    }
    else
    {
        $msg = "Please enter the details";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=purchases.php?msg=$msg'>   
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
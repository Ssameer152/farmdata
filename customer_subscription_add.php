<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_POST['cid']) && isset($_POST['sdate']) && isset($_POST['qty']) && $_POST['qty']!='' && isset($_POST['milktype']) && $_POST['milktype']!='' && isset($_POST['dltime']) && $_POST['dltime']!='')
    {
        $stdate = mysqli_real_escape_string($db,$_POST['sdate']);
        $qty = mysqli_real_escape_string($db,$_POST['qty']);
        $cid = mysqli_real_escape_string($db,$_POST['cid']);
        $milktype = mysqli_real_escape_string($db,$_POST['milktype']);
        $dltime = mysqli_real_escape_string($db,$_POST['dltime']);

        if(isset($_POST['mid']) && $_POST['mid']!='')
        {
            $mid = $_POST['mid'];
            $q = "UPDATE customer_subscription SET start_date='$stdate',qty='$qty',milktype='$milktype',delivery_time='$dltime' WHERE id='$mid' LIMIT 1";
            $r = mysqli_query($db,$q);
            
            $msg = 'Updated';
        }
        else{
        $q = "INSERT INTO customer_subscription(start_date,qty,cid,milktype,delivery_time) VALUES('$stdate','$qty','$cid','$milktype','$dltime')";
        $r = mysqli_query($db,$q);
        
        $msg = "Customer subscription Added";
        }
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=customer_subscription.php?cid=$cid&msg=$msg'>
_END;
        
    }
    else
    {
        $msg = "Please enter the details";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=customer_subscription.php?msg=$msg'>   
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
<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    
    if(isset($_POST['particular']) && isset($_POST['rec']) && isset($_POST['paid']) && isset($_POST['dot']) && $_POST['particular']!='' && $_POST['rec']!='' && $_POST['paid']!='' && $_POST['dot']!='')
    {
        $particular = mysqli_real_escape_string($db,$_POST['particular']);
        $rec = mysqli_real_escape_string($db,$_POST['rec']);
        $paid = mysqli_real_escape_string($db,$_POST['paid']);
        $dot = mysqli_real_escape_string($db,$_POST['dot']);
        
        $q = "INSERT INTO transactions(dot,particular,amt_paid,amt_received) VALUES('$dot','$particular','$paid','$rec')";
        $r = mysqli_query($db,$q);
        
        $msg = "User Added";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=transactions.php?msg=$msg'>
_END;
        
    }
    else
    {
        $msg = "Please enter the details";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=areas.php?msg=$msg'>   
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
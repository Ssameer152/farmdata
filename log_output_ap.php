<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    
    if(isset($_POST['area']) && $_POST['area']!='' && isset($_POST['qty']) && $_POST['qty']!='' && isset($_POST['logid']) && $_POST['logid']!='' && isset($_POST['person']) && $_POST['person']!=''&& isset($_POST['comments']))
    {
        $area = mysqli_real_escape_string($db,$_POST['area']);
        $qty = mysqli_real_escape_string($db,$_POST['qty']);
        $logid = mysqli_real_escape_string($db,$_POST['logid']);
        $person = mysqli_real_escape_string($db,$_POST['person']);
        $comments = mysqli_real_escape_string($db,$_POST['comments']);
        
        
        if(isset($_POST['mid']) && $_POST['mid']!='')
        {
            $mid = $_POST['mid'];
            $q = "UPDATE log_output SET resourceid='$area',person='$person',qty='$qty',comments='$comments' WHERE id='$mid' LIMIT 1";
            $r = mysqli_query($db,$q);
            
            $msg = 'Updated';
        }
        else{

        $q = "INSERT INTO log_output(logid,resourceid,qty,person,comments) VALUES('$logid','$area','$qty','$person','$comments')";
        $r = mysqli_query($db,$q);
        
        if(!$r){
            echo mysqli_error($db);
        }
        
        $msg = "Log Added";
        }
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=log_output.php?logid=$logid&msg=$msg'>
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
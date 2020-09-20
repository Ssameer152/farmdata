<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_POST['cactivity']) && $_POST['cactivity']!='' && isset($_POST['acvalue']) && $_POST['acvalue']!='' && isset($_POST['comments']) && isset($_POST['doa']) && $_POST['doa']!='' && isset($_POST['cid']))
    {
        $ctactivity = mysqli_real_escape_string($db,$_POST['cactivity']);
        $acvalue = mysqli_real_escape_string($db,$_POST['acvalue']);
        $comments = mysqli_real_escape_string($db,$_POST['comments']);
        $doa=mysqli_real_escape_string($db,$_POST['doa']);
        $cid = mysqli_real_escape_string($db,$_POST['cid']);

        $q = "INSERT INTO cattle_activity_log(cid,caid,activity_value,comments,doa) VALUES('$cid','$ctactivity','$acvalue','$comments','$doa')";
        $r = mysqli_query($db,$q);
        
        $msg = "Cattle Activity Log Added";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=cattle_activity_log.php?cid=$cid&msg=$msg'>
_END;
        
    }
    else
    {
        $msg = "Please enter the details";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=cattle.php?msg=$msg'>   
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
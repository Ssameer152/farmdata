<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_POST['cactivity']) && $_POST['cactivity']!='' && isset($_POST['acvalue']) && $_POST['acvalue']!='' && isset($_POST['comments']) && isset($_POST['doa']) && $_POST['doa']!='' && isset($_POST['cattle']) && $_POST['cattle'])
    {
        $ctactivity = mysqli_real_escape_string($db,$_POST['cactivity']);
        $acvalue = mysqli_real_escape_string($db,$_POST['acvalue']);
        $comments = mysqli_real_escape_string($db,$_POST['comments']);
        $doa=mysqli_real_escape_string($db,$_POST['doa']);
        $cid = mysqli_real_escape_string($db,$_POST['cattle']);
        if(isset($_POST['mid']) && $_POST['mid']!='')
        {
            $mid = $_POST['mid'];
            $q="UPDATE cattle_activity_log set cid='$cid',caid='$ctactivity',activity_value='$acvalue',comments='$comments',doa='$doa' where id='$mid' LIMIT 1";
            $r=mysqli_query($db,$q);
            $msg="Activity log Updated";
        }
        else{
        $q = "INSERT INTO cattle_activity_log(cid,caid,activity_value,comments,doa) VALUES('$cid','$ctactivity','$acvalue','$comments','$doa')";
        $r = mysqli_query($db,$q);
        
        $msg = "Cattle Activity Log Added";
        }
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=cattle_activity_log_avg.php?cid=$cid&msg=$msg'>
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
<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_POST['activity']) && $_POST['activity']!='')
    {
        $activity = mysqli_real_escape_string($db,$_POST['activity']);
        if(isset($_POST['mid']) && $_POST['mid']!='')
        {
            $mid = $_POST['mid'];
            $q="UPDATE activities set activity='$activity' where id='$mid' LIMIT 1";
            $r=mysqli_query($db,$q);
            $msg="Activity Updated";
        }

        else{
        $q = "INSERT INTO activities(activity) VALUES('$activity')";
        $r = mysqli_query($db,$q);
        
        $msg = "Activity Added";
        }
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=activity.php?msg=$msg'>
_END;
        
    }
    else
    {
        $msg = "Please enter the details";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=activity.php?msg=$msg'>   
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
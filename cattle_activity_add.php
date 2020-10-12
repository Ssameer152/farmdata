<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_POST['activity']) && $_POST['activity']!='')
    {
        $cattleactivity = mysqli_real_escape_string($db,$_POST['activity']);
        
        if(isset($_POST['mid']) && $_POST['mid']!='')
        {
            $mid = $_POST['mid'];
            $q="UPDATE cattle_activity set name='$cattleactivity' where id='$mid' LIMIT 1";
            $r=mysqli_query($db,$q);
            $msg="Activity Updated";
        }
        else{
        $q = "INSERT INTO cattle_activity(name) VALUES('$cattleactivity')";
        $r = mysqli_query($db,$q);
        
        $msg = "Cattle Activity Added";
        }
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=cattle_activity.php?msg=$msg'>
_END;
        
    }
    else
    {
        $msg = "Please enter the details";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=cattle_activity.php?msg=$msg'>   
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
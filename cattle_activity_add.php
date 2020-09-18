<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_POST['activity']) && $_POST['activity']!='')
    {
        $cattleactivity = mysqli_real_escape_string($db,$_POST['activity']);
        
        $q = "INSERT INTO cattle_activity(name) VALUES('$cattleactivity')";
        $r = mysqli_query($db,$q);
        
        $msg = "Cattle Activity Added";
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
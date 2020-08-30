<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_POST['resource']) && $_POST['resource']!='' && isset($_POST['unit']))
    {
        $resource = mysqli_real_escape_string($db,$_POST['resource']);
        $unit = mysqli_real_escape_string($db,$_POST['unit']);
        $q = "INSERT INTO resources(resourcename,unit) VALUES('$resource','$unit')";
        $r = mysqli_query($db,$q);
        
        $msg = "Resource Added";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=resources.php?msg=$msg'>
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
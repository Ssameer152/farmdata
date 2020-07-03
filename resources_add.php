<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_POST['resource']) && $_POST['resource']!='')
    {
        $resource = mysqli_real_escape_string($db,$_POST['resource']);
        
        $q = "INSERT INTO resources(resourcename) VALUES('$resource')";
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
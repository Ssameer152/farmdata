<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_POST['cattle']) && $_POST['cattle']!='')
    {
        $cattletype = mysqli_real_escape_string($db,$_POST['cattle']);
        
        $q = "INSERT INTO cattle_type(name) VALUES('$cattletype')";
        $r = mysqli_query($db,$q);
        
        $msg = "Cattle Type Added";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=cattle_type.php?msg=$msg'>
_END;
        
    }
    else
    {
        $msg = "Please enter the details";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=cattle_type.php?msg=$msg'>   
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
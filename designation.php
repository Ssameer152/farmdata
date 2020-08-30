<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_POST['designation']) && $_POST['designation']!='')
    {
        $desig = mysqli_real_escape_string($db,$_POST['designation']);
        
        $q = "INSERT INTO designation(desig) VALUES('$desig')";
        $r = mysqli_query($db,$q);
        
        $msg = "Designation Added";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=people.php?msg=$msg'>
_END;
        
    }
    else
    {
        $msg = "Please enter the details";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=people.php?msg=$msg'>   
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
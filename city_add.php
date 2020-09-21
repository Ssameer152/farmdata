<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_POST['city']) && $_POST['city']!='' && isset($_POST['state']) && $_POST['state']!='')
    {
        $city = mysqli_real_escape_string($db,$_POST['city']);
        $state = mysqli_real_escape_string($db,$_POST['state']);
        $q = "INSERT INTO city(name,state_id) VALUES('$city','$state')";
        $r = mysqli_query($db,$q);
        
        $msg = "City Added";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=city.php?msg=$msg'>
_END;
        
    }
    else
    {
        $msg = "Please enter the details";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=city.php?msg=$msg'>   
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
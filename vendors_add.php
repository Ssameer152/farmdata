<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    
    if(isset($_POST['vname']) && isset($_POST['mobile']) && isset($_POST['email']) && isset($_POST['cperson']) && isset($_POST['address']))
    {
        $vname = mysqli_real_escape_string($db,$_POST['vname']);
        $mobile = mysqli_real_escape_string($db,$_POST['mobile']);
        $email = mysqli_real_escape_string($db,$_POST['email']);
        $cperson = mysqli_real_escape_string($db,$_POST['cperson']);
        $address = mysqli_real_escape_string($db,$_POST['address']);
        
        $q = "INSERT INTO vendor(name,address,email,phone,contact_person) VALUES('$vname','$address','$email','$mobile','$cperson')";
        $r = mysqli_query($db,$q);
        
        $msg = "Record Added";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=vendor.php?msg=$msg'>
_END;
        
    }
    else
    {
        $msg = "Please enter the details";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=vendor.php?msg=$msg'>   
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
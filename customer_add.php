<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_POST['fname']) && $_POST['fname']!='' && isset($_POST['lname']) && isset($_POST['email']) && isset($_POST['phone']) && $_POST['phone']!='' && isset($_POST['zipcode']) && isset($_POST['city']) && isset($_POST['state']) && isset($_POST['address']) && $_POST['address']!='')
    {
        $fname = mysqli_real_escape_string($db,$_POST['fname']);
        $lname = mysqli_real_escape_string($db,$_POST['lname']);
        $email = mysqli_real_escape_string($db,$_POST['email']);
        $phone = mysqli_real_escape_string($db,$_POST['phone']);
        $zip = mysqli_real_escape_string($db,$_POST['zipcode']);
        $city = mysqli_real_escape_string($db,$_POST['city']);
        $state = mysqli_real_escape_string($db,$_POST['state']);
        $address = mysqli_real_escape_string($db,$_POST['address']);
        if(isset($_POST['mid']) && $_POST['mid']!='')
        {
            $mid = $_POST['mid'];
            $q = "UPDATE customer SET fname='$fname',lname='$lname',email='$email',phone='$phone',zipcode='$zip',city='$city',state='$state',address='$address' WHERE id='$mid' LIMIT 1";
            $r = mysqli_query($db,$q);
            
            $msg = 'Updated';
        }
        else{
        $q = "INSERT INTO customer(fname,lname,email,phone,zipcode,city,state,address) VALUES('$fname','$lname','$email','$phone','$zip','$city','$state','$address')";
        $r = mysqli_query($db,$q);
        
        $msg = "Customer Added";
        }
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=customer.php?msg=$msg'>
_END;
        
    }
    else
    {
        $msg = "Please enter the details";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=customer.php?msg=$msg'>   
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
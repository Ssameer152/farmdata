<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_POST['fname']) && $_POST['fname']!='' && isset($_POST['desig']) && $_POST['desig']!='' && isset($_POST['lname']) && isset($_POST['email']) && $_POST['email']!='' && isset($_POST['phone']) && $_POST['phone']!='' && isset($_POST['joined_on']) && isset($_POST['pword']) && $_POST['joined_on']!='' && $_POST['pword']!='')
    {
        $fname = mysqli_real_escape_string($db,$_POST['fname']);
        $desig = mysqli_real_escape_string($db,$_POST['desig']);
        $lname = mysqli_real_escape_string($db,$_POST['lname']);
        $email = mysqli_real_escape_string($db,$_POST['email']);
        $phone = mysqli_real_escape_string($db,$_POST['phone']);
        $joined_on = mysqli_real_escape_string($db,$_POST['joined_on']);
        $pword = mysqli_real_escape_string($db,$_POST['pword']);
        
        if(isset($_POST['mid']) && $_POST['mid']!='')
        {
            $mid = $_POST['mid'];
            $q="UPDATE people set fname='$fname',lname='$lname',email='$email', phone='$phone',joined_on='$joined_on',designation='$desig',pword='$pword' where id='$mid' LIMIT 1";
            $r=mysqli_query($db,$q);
            $msg="Updated";
        }
        else{
        $q = "INSERT INTO people(fname,lname,email,phone,joined_on,designation,pword) VALUES('$fname','$lname','$email','$phone','$joined_on','$desig','$pword')";
        $r = mysqli_query($db,$q);
        
        $msg = "User Added";
        }
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
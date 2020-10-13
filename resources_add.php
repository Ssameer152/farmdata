<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_POST['resource']) && $_POST['resource']!='' && isset($_POST['unit']))
    {
        $resource = mysqli_real_escape_string($db,$_POST['resource']);
        $unit = mysqli_real_escape_string($db,$_POST['unit']);

        if(isset($_POST['mid']) && $_POST['mid']!='')
        {
            $mid = $_POST['mid'];
            $q="UPDATE resources set resourcename='$resource',unit='$unit' where id='$mid' LIMIT 1";
            $r=mysqli_query($db,$q);
            $msg="Updated";
        }
        else{
        $q = "INSERT INTO resources(resourcename,unit) VALUES('$resource','$unit')";
        $r = mysqli_query($db,$q);
        
        $msg = "Resource Added";
        }
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
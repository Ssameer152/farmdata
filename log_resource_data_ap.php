<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    
    if(isset($_POST['rad']) && $_POST['rad']!='' && isset($_POST['person']) && $_POST['person']!='' && isset($_POST['resource']) && $_POST['resource']!='' && isset($_POST['qty']) && $_POST['qty']!='' && isset($_POST['logid']) && $_POST['logid']!='' && isset($_POST['comments']))
    {
        $type = mysqli_real_escape_string($db,$_POST['rad']);
        $qty = mysqli_real_escape_string($db,$_POST['qty']);
        $resource = mysqli_real_escape_string($db,$_POST['resource']);
        $logid = mysqli_real_escape_string($db,$_POST['logid']);
        $person = mysqli_real_escape_string($db,$_POST['person']);
        $comments = mysqli_real_escape_string($db,$_POST['comments']);
        
        if($type==1){
            $q="INSERT INTO log_output(logid,resourceid,qty,person,comments) VALUES('$logid','$resource','$qty','$person','$comments')";
            $r=mysqli_query($db,$q);
            $msg = "Work Log Added";
        }
       
        elseif($type==2){
            $q = "INSERT INTO log_resource(logid,resourceid,qty,costperunit,person) VALUES('$logid','$resource','$qty',0.00,'$person')";
            $r = mysqli_query($db,$q);

            $msg = "Work Log Added";
        }
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=log_resource_data.php?msg=$msg'>
_END;
    }
    else
    {
        $msg = "Please select all the fields";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=logs.php?msg=$msg'>
_END;
    }
}
else
{
    $msg = "Please login!";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}

?>
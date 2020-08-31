<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    
    if(isset($_POST['area']) && $_POST['area']!='' && isset($_POST['people']) && $_POST['people']!='' && isset($_POST['activity']) && $_POST['activity']!='')
    {
        $area = mysqli_real_escape_string($db,$_POST['area']);
        $people = mysqli_real_escape_string($db,$_POST['people']);
        $activity = mysqli_real_escape_string($db,$_POST['activity']);
        
        $sd = mysqli_real_escape_string($db,$_POST['startdate']);
        
        $qc = "SELECT * FROM logs WHERE activity='$activity' and cast(doe as date)='$sd' and is_deleted=0";
        $rc = mysqli_query($db,$qc);
        
        if(mysqli_num_rows($rc)>0)
        {
            $msg = "Work log already exists!";
            echo <<<_END
            <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
        }
        else
        {
            
            $q = "INSERT INTO logs(area,activity,people,doe) VALUES('$area','$activity','$people','$sd')";
            $r = mysqli_query($db,$q);
            
            if(!$r)
            {
                echo mysqli_error($db);
            }
        
            $msg = "Work Log Added";
            echo <<<_END
            <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
        }
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
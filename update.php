<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    
    if(isset($_GET['table']) && isset($_GET['rid']) && isset($_GET['return']) && $_GET['table']!='' && $_GET['rid']!='' && $_GET['return']!=''){
        $table = $_GET['table'];
        $rid = $_GET['rid'];
        $return = $_GET['return'];


        if(isset($_GET['ps'])){
            $ps = $_GET['ps'];
            $q = "UPDATE $table SET payment_status=1 WHERE id='$rid' LIMIT 1";
            $r = mysqli_query($db,$q);
        }
        else{
            $ps = '';
        }
        if(isset($_GET['ds'])){
            $ds = $_GET['ds'];
            $q = "UPDATE $table SET delivery_status=1 WHERE id='$rid' LIMIT 1";
            $r = mysqli_query($db,$q);
        }
        else{
            $ds = '';
        }
        if(isset($_GET['ds']) && isset($_GET['ps']) && isset($_GET['s'])){
            $ds = $_GET['ds'];
            $ps=$_GET['ps'];
            $s=$_GET['s'];
            $q = "UPDATE $table SET status=1 WHERE id='$rid'  LIMIT 1";
            $r = mysqli_query($db,$q);
        }
        else{
            $ds = '';
            $ps='';
        }
        
       
        
        $msg = 'Record updated';
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=$return.php?ps=$ps&ds=$ds&msg=$msg'>
_END;
        
    }
    else{
        $msg = "Something Went Wrong!!";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
    }
}
else{
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}

?>
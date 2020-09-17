<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    
    if(isset($_POST['name']) && $_POST['name']!='' && isset($_POST['ctype']) && $_POST['ctype']!='' && isset($_POST['pdate']) && $_POST['pdate']!='' && isset($_POST['breed']) && $_POST['breed']!=''&& isset($_POST['age']) && $_POST['age']!='')
    {
        $cname = mysqli_real_escape_string($db,$_POST['name']);
        $ctype = mysqli_real_escape_string($db,$_POST['ctype']);
        $pdate = mysqli_real_escape_string($db,$_POST['pdate']);
        $breed = mysqli_real_escape_string($db,$_POST['breed']);
        $age = mysqli_real_escape_string($db,$_POST['age']);
        
        if(isset($_POST['mid']) && $_POST['mid']!='')
        {
            $mid = $_POST['mid'];
            $q = "UPDATE cattle SET name='$cname',type_id='$ctype',date_purchase='$pdate',breed_id='$breed',age_when_purchased='$age' WHERE id='$mid' LIMIT 1";
            $r = mysqli_query($db,$q);
            
            $msg = 'Updated';
        }
        else{
            $q = "INSERT INTO cattle(name,type_id,date_purchase,breed_id,age_when_purchased) VALUES('$cname','$ctype','$pdate','$breed','$age')";
            $r = mysqli_query($db,$q);
            
            $msg = "Cattle Added";
        }
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=cattle.php?msg=$msg'>
_END;
    }
    else
    {
        $msg = "Please select all the fields";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=cattle.php?msg=$msg'>
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
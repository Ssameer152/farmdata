<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_POST['breed']) && $_POST['breed']!='')
    {
        $cattlebreed = mysqli_real_escape_string($db,$_POST['breed']);

        if(isset($_POST['mid']) && $_POST['mid']!='')
        {
            $mid = $_POST['mid'];
            $q="UPDATE cattle_breed set breed='$cattlebreed' where id='$mid' LIMIT 1";
            $r=mysqli_query($db,$q);
            $msg="Breed Updated";
        }
        else{
        $q = "INSERT INTO cattle_breed(breed) VALUES('$cattlebreed')";
        $r = mysqli_query($db,$q);
        
        $msg = "Cattle Breed Added";
        }
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=cattle_breed.php?msg=$msg'>
_END;
        
    }
    else
    {
        $msg = "Please enter the details";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=cattle_breed.php?msg=$msg'>   
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
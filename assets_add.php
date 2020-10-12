<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_POST['asset']) && $_POST['asset']!='')
    {
        $asset = mysqli_real_escape_string($db,$_POST['asset']);
        if(isset($_POST['mid']) && $_POST['mid']!='')
        {
            $mid = $_POST['mid'];
            $q="UPDATE assets set assetname='$asset' where id='$mid' LIMIT 1";
            $r=mysqli_query($db,$q);
            $msg="Asset Updated";
        }
        else{
        $q = "INSERT INTO assets(assetname) VALUES('$asset')";
        $r = mysqli_query($db,$q);
        
        $msg = "Asset Added";
        }
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=assets.php?msg=$msg'>
_END;
        
    }
    else
    {
        $msg = "Please enter the details";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=assets.php?msg=$msg'>   
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
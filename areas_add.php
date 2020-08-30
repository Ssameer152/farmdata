<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_POST['sitename']) && isset($_POST['location']) && isset($_POST['areasize']) && isset($_POST['manager']) && isset($_POST['rent']) && isset($_POST['leased_until']) && $_POST['sitename']!='' && $_POST['location']!='' && $_POST['areasize']!='' && $_POST['manager']!='' && $_POST['rent']!='' && $_POST['leased_until']!='')
    {
        $sitename = $_POST['sitename'];
        $location = $_POST['location'];
        $areasize = $_POST['areasize'];
        $manager = $_POST['manager'];
        $rent = $_POST['rent'];
        $leased_until = $_POST['leased_until'];
        
        $q = "INSERT INTO areas(sitename,location,area,manager,monthly_rent,leased_until) VALUES('$sitename','$location','$areasize','$manager','$rent','$leased_until')";
        $r = mysqli_query($db,$q);
        
        $msg = "User Added";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=areas.php?msg=$msg'>
_END;
        
    }
    else
    {
        $msg = "Please enter the details";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=areas.php?msg=$msg'>   
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
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

        if(isset($_POST['mid']) && $_POST['mid']!='')
        {
            $mid = $_POST['mid'];
            $q="UPDATE areas set sitename='$sitename',location='$location',area='$areasize',manager='$manager',monthly_rent='$rent',leased_until='$leased_until' where id='$mid' LIMIT 1";
            $r=mysqli_query($db,$q);
            $msg="Updated";
        }
        else{
        $q = "INSERT INTO areas(sitename,location,area,manager,monthly_rent,leased_until) VALUES('$sitename','$location','$areasize','$manager','$rent','$leased_until')";
        $r = mysqli_query($db,$q);
        
        $msg = "Area Added";
        }
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
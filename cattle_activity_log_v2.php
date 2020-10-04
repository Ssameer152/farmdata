<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';


if(isset($_GET['cid']) && $_GET['cid']!='')
{
    
    echo <<<_END
<html>
    <head>
        <title>FarmDB</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
    </head>
    
    <body>    
_END;

include_once 'nav.php';

$cid = mysqli_real_escape_string($db,$_GET['cid']);
$q = "SELECT * FROM cattle WHERE id='$cid'";
$r = mysqli_query($db,$q);

$res = mysqli_fetch_assoc($r);

$cname = $res['name'];

echo <<<_END

		<div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2>Cattle Activity For Cattle #$cid ($cname)</h2>
_END;


if(isset($_GET['date'])){
    $date = $_GET['date'];
    
    echo <<<_END
                    <form action="cattle_activity_log_v2_ap.php" method="post">
                        <div class="form-group">
                            <label for="activity">Date</label>
                            <input type="date" name="date" class="form-control" value="$date" disabled>
                            <input type="hidden" name="cid" value="$cid">
                        </div>
_END;

$q2 = "SELECT * FROM cattle_activity WHERE is_deleted=0";
$r2 = mysqli_query($db,$q2);

while($res = mysqli_fetch_assoc($r2)){
    $aid = $res['id'];
    $aname = $res['name'];
    
    
    $q3 = "SELECT * FROM cattle_activity_log WHERE cid='$cid' AND caid='$aid' AND cast(doa as date)='$date'";
    $r3 = mysqli_query($db,$q3);
    
    $row3 = mysqli_num_rows($r3);
    
    if($row3 > 0){
        $re3 = mysqli_fetch_assoc($r3);
        $value = $re3['activity_value'];
        $comment_value = $re3['comments'];
    }
    else{
        $value = 0;
        $comment_value = '';
    }


echo <<<_END
    <div class="form-group">
        <label for="$aname">$aname</label>
        <input type="text" name="$aid" class="form-control" value="$value">
        <input type="text" name="comment_$aid" class="form-control" placeholder="comment" value="$comment_value">
    </div>

_END;


}

echo <<<_END
						<button type="submit" class="btn btn-primary">Update</button>
                    </form>
_END;
    
}
else{
echo <<<_END
                    <form action="cattle_activity_log_v2.php" method="get">
                        <div class="form-group">
                            <label for="activity">Date</label>
                            <input type="date" name="date" class="form-control">
                            <input type="hidden" name="cid" value="$cid">
                        </div>
						<button type="submit" class="btn btn-primary">Apply</button>
                    </form>
_END;
}
                    
                    
echo <<<_END
                </div>
                
            </div>
        </div>

_END;

include_once 'foot.php';

echo <<<_END
    </body>
</html>
_END;


}
else{
    $msg = "Please select a cattle";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=cattle.php?msg=$msg'>
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

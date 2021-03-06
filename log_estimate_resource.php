<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';


if(isset($_GET['logid']) && $_GET['logid']!='')
{    
    $logid = $_GET['logid'];
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

echo <<<_END

		<div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2>Log Resources</h2>
_END;


    echo <<<_END
                        <form action="log_estimate_resource_ap.php" method="post">
    <div class="form-group">
        <label for="area">Resource</label>
        <select name="area" class="form-control">
            <option value="">--Select Resource--</option>
_END;

$q = "SELECT * FROM resources WHERE is_deleted=0";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sid = $res['id'];
    $rname = $res['resourcename'];
    $unit = $res['unit'];
    
    echo <<<_END
    <option value="$sid">$rname ($unit)</option>
_END;

}

echo <<<_END
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="text" name="qty" class="form-control">
                        </div>
                        <input type="hidden" name="logid" value="$logid">
						<button type="submit" class="btn btn-primary">Add Resource Estimate</button>
                    </form>
_END;


echo <<<_END
                </div>
<div class="col-lg-12">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Resource</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
_END;

$q = "SELECT sum(qty) as qty,resourceid FROM estimate_log_resource WHERE logid='$logid' GROUP BY resourceid";
$r = mysqli_query($db,$q);
$sn = 0;
while($res = mysqli_fetch_assoc($r))
{
    $qty = $res['qty'];
    $rid = $res['resourceid'];
    
    $q2 = "SELECT resourcename,unit FROM resources WHERE id='$rid'";
    $r2 = mysqli_query($db,$q2);
    
    $re2 = mysqli_fetch_assoc($r2);
    
    $unit = $re2['unit'];
    $resourcename  = $re2['resourcename'];
    $sn = $sn + 1;
    echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$resourcename</td>
        <td>$qty $unit</td>
    </tr>
_END;
}

echo <<<_END
            </tbody>
        </table>
    </div>

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
else
{
    $msg = "Please select a work log";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
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
